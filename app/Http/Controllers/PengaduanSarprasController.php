<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Http\Sheet\Sheet;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\Periode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengaduanSarprasController extends Controller
{
    // Fitur Pengaduan yang akan di inspeksi
    // -User : Sarpras
    public function index(Request $request)
    {
        $activeMenu = 'pengaduan';

        $tanggalSekarang = Carbon::now()->day;

        if($tanggalSekarang > 15) {
            $breadcrumb = (object) [
                'title' => '',
                'list' => []
            ];

            $pesan = 'Maaf, Anda tidak dapat mengakses menu ini setelah tanggal 15. Silakan tunggu hingga periode berikutnya pada tanggal <span class="font-semibold">1–15</span>.';

            return view ('access.denied', [
                'activeMenu' => $activeMenu, 
                'breadcrumb' => $breadcrumb,
                'pesan' => $pesan,
            ]);
        }

        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Home', 'Laporan Pengaduan']
        ];

        $page = (object) [
            'title' => 'Daftar Pengaduan dari Semua Periode Sebelumnya untuk Penugasan Inspeksi'
        ];

        $pelapor = '';

        // Ambil periode aktif saat ini
        $periodeSekarang = Periode::getPeriodeAktif();

        // Query untuk Pengaduan dari semua periode kecuali periode aktif
        $query = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor.role', 'inspeksi'])
            ->whereHas('aduan', function ($query) use ($periodeSekarang) {
                $query->where('status', Status::MENUNGGU_DIPROSES->value)
                      ->where('id_periode', '!=', $periodeSekarang->id_periode);
            });

        // Filter berdasarkan role pelapor
        if ($request->has('filter_user') && $request->filter_user != 'all') {
            $pelapor = match ($request->filter_user) {
                '1' => ' Mahasiswa',
                '5' => ' Dosen',
                '6' => ' Tendik',
                default => ''
            };

            $filterRole = $request->filter_user;

            // Filter hanya fasilitas yang punya aduan dari pelapor dengan role tersebut
            $query->whereHas('aduan', function ($q) use ($filterRole, $periodeSekarang) {
                $q->whereHas('pelapor', function ($q2) use ($filterRole) {
                    $q2->where('id_role', $filterRole);
                })->where('id_periode', '!=', $periodeSekarang->id_periode);
            });
        }

        // Filter berdasarkan pencarian nama fasilitas
        if ($request->search) {
            $query->where('nama_fasilitas', 'like', "%{$request->search}%");
        }

        // Hitung skor berdasarkan bobot pelapor
        $query->withCount([
            'aduan as skor_bobot' => function ($q) use ($periodeSekarang, $request) {
                $q->where('status', Status::MENUNGGU_DIPROSES->value)
                  ->where('id_periode', '!=', $periodeSekarang->id_periode)
                  ->select(DB::raw('SUM(CASE
                        WHEN users.id_role = 1 THEN 1
                        WHEN users.id_role = 5 THEN 2 
                        WHEN users.id_role = 6 THEN 3 
                        ELSE 0 
                    END)'))
                  ->join('users', 'aduan.id_user_pelapor', '=', 'users.id_user');

                  // Filter berdasarkan role jika filter_user ada
                if ($request->has('filter_user') && $request->filter_user != 'all') {
                    $filterRole = $request->filter_user;
                    $q->whereHas('pelapor', function ($q2) use ($filterRole) {
                        $q2->where('id_role', $filterRole);
                    });
                }
            }
        ]);

        // Urutkan berdasarkan skor bobot secara menurun
        $query->orderBy('skor_bobot', 'desc')->limit(10);

        // Pagination
        $perPage = 10;
        $pengaduan = $query->paginate($perPage);
        $pengaduan->appends(request()->query());

        if ($request->ajax()) {
            $html = view('sarpras.pengaduan.table', compact('pengaduan', 'pelapor'))->render();
            return response()->json(['html' => $html]);
        }

        return view('sarpras.pengaduan.index', compact('breadcrumb', 'page', 'activeMenu', 'pengaduan', 'pelapor'));
    }

    // Detail Fasilitas & Laporan Pengaduan nya
    public function show_pengaduan($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor'])->withCount('aduan')->findOrFail($id);
        $aduan = $fasilitas->aduan->first();
        // dd($aduan);
        return view('sarpras.pengaduan.detail', ['fasilitas' => $fasilitas, 'aduan' => $aduan]);
    }

    // Method menugaskan teknisi untuk inspeksi
    public function penugasan_teknisi($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor'])->withCount('aduan')->findOrFail($id);
        $aduan = $fasilitas->aduan->first();
        $teknisi = User::where('id_role', 3)->get();
        return view('sarpras.pengaduan.edit', ['fasilitas' => $fasilitas, 'aduan' => $aduan, 'teknisi' => $teknisi]);
    }

    public function confirmed_penugasan(Request $request, $id)
    {
        $periodeSekarang = Periode::getPeriodeAktif();

        try {
            Inspeksi::create([
                'id_user_teknisi' => $request->id_teknisi,
                'id_user_sarpras' => auth()->user()->id_user,
                'id_fasilitas' => $id,
                'id_periode' => $periodeSekarang->id_periode,
                'tanggal_mulai' => now(),
            ]);
            $aduan = Aduan::with('pelapor')->where('id_fasilitas', $id)->where('status', Status::MENUNGGU_DIPROSES->value)->get();
            $fasilitas = Fasilitas::where('id_fasilitas', $id)->value('nama_fasilitas');
            // dd($aduan);
            foreach ($aduan as $a) {
                $a->update(['status' => Status::SEDANG_INSPEKSI->value]);

                // Notifikasi ke pelapor (versi panjang)
                Notifikasi::create([
                    'pesan' => 'Fasilitas <b class="text-red-500">' . $fasilitas . '</b> yang Anda laporkan sedang dalam proses inspeksi oleh teknisi. Kami akan memberi informasi lebih lanjut setelah inspeksi selesai.',
                    'waktu_kirim' => now(),
                    'id_user' => $a->pelapor->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Notifikasi ke teknisi
            Notifikasi::create([
                'pesan' => 'Anda telah ditugaskan untuk melakukan inspeksi terhadap fasilitas <b  class="text-red-500">' . $fasilitas . '</b>. Silakan lakukan pengecekan dan catat hasil temuan Anda.',
                'waktu_kirim' => now(),
                'id_user' => $request->id_teknisi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Berhasil menugaskan inspeksi.');
        } catch (\Exception $e) {
            Log::error('Gagal menugaskan teknisi: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menugaskan teknisi.']);
        }
    }

    // private function set_sheet($filter_user)
    // {
    //     $query = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor.role', 'inspeksi'])
    //         ->whereHas('aduan', function ($query) {
    //             $query->where('status', Status::MENUNGGU_DIPROSES->value);
    //         });

    //     if ($filter_user != 'all') {
    //         $pelapor = match ($filter_user) {
    //             '1' => ' dari Mahasiswa',
    //             '5' => ' dari Dosen',
    //             '6' => ' dari Tendik',
    //             default => '' // Opsional: untuk menangani nilai yang tidak sesuai
    //         };

    //         // Filter dan count hanya aduan dari pelapor dengan role tertentu
    //         $query->withCount([
    //             'aduan as aduan_count' => function ($q) use ($filter_user) {
    //                 $q->whereHas('pelapor', function ($q2) use ($filter_user) {
    //                     $q2->where('id_role', $filter_user);
    //                 });
    //             }
    //         ]);

    //         // Filter hanya fasilitas yang punya aduan dari pelapor dengan role tersebut
    //         $query->whereHas('aduan', function ($q) use ($filter_user) {
    //             $q->whereHas('pelapor', function ($q2) use ($filter_user) {
    //                 $q2->where('id_role', $filter_user);
    //             });
    //         });
    //     } else {
    //         // Jika tidak difilter berdasarkan role
    //         $query->withCount('aduan');
    //     }

    //     $query->orderBy('aduan_count', 'desc')->get();

    //     $filename = 'data_pengaduan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    //     $sheet = new Sheet(); // Pass the data and filename to the Sheet
    //     $sheet->title = 'Data Proses Pengecekan Pengaduan';
    //     $sheet->text = 'Berikut adalah Data Proses Pengecekan Pengaduan yang masuk' . $pelapor . '.';
    //     $sheet->footer = 'Dibuat oleh Sistem';
    //     $sheet->header = ['Kode Fasilitas', 'Nama Fasilitas', 'Kategori', 'Lokasi', 'Urgensi', 'Jumlah Aduan'];

    //     $sheet->data = $query->map(function ($item) {
    //         return [
    //             'nama_fasilitas' => $item->fasilitas->nama_fasilitas,
    //             'kategori' => $item->fasilitas->kategori->nama_kategori,
    //             'tanggal_aduan' => $item->tanggal_aduan,
    //             'tanggal_perbaikan' => $item->fasilitas->inspeksi->first()->perbaikan ? $item->fasilitas->inspeksi->first()->perbaikan->tanggal_selesai : 'Belum diperbaiki',
    //         ];
    //     })->toArray();
    //     $sheet->filename = $filename;

    //     return $sheet;
    // }
    // public function export_excel($filter_role)
    // {

    //     return $this->set_sheet($filter_role)->toXls();
    // }

    // public function export_pdf()
    // {
    //     // return $this->set_sheet()->toPdf();
    // }
}
