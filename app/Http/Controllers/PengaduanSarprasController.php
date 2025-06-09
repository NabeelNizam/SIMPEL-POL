<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Http\Sheet\Sheet;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Kategori;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengaduanSarprasController extends Controller
{
    // Fitur Pengaduan yang akan di inspeksi
    // -User : Sarpras
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Home', 'Laporan Pengaduan']
        ];

        $page = (object) [
            'title' => 'Daftar Pengaduan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengaduan';
        $pelapor = '';

        // Query untuk Pengaduan
        $query = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor.role', 'inspeksi'])
            ->whereHas('aduan', function ($query) {
                $query->where('status', Status::MENUNGGU_DIPROSES->value);
            });

        if ($request->has('filter_user') && $request->filter_user != 'all') {
            $pelapor = match ($request->filter_user) {
                '1' => ' Mahasiswa',
                '5' => ' Dosen',
                '6' => ' Tendik',
                default => '' // Opsional: untuk menangani nilai yang tidak sesuai
            };

            $filterRole = $request->filter_user;

            // Filter dan count hanya aduan dari pelapor dengan role tertentu
            $query->withCount([
                'aduan as aduan_count' => function ($q) use ($filterRole) {
                    $q->whereHas('pelapor', function ($q2) use ($filterRole) {
                        $q2->where('id_role', $filterRole);
                    });
                }
            ]);

            // Filter hanya fasilitas yang punya aduan dari pelapor dengan role tersebut
            $query->whereHas('aduan', function ($q) use ($filterRole) {
                $q->whereHas('pelapor', function ($q2) use ($filterRole) {
                    $q2->where('id_role', $filterRole);
                });
            });
        } else {
            // Jika tidak difilter berdasarkan role
            $query->withCount('aduan');
        }

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->where('nama_fasilitas', 'like', "%{$request->search}%");
        }

        $periodeSekarang = Periode::getPeriodeAktif();
        // Filter berdasarkan periode
        if ($request->id_periode) {
            $query->whereHas('aduan', function ($q) use ($request) {
                $q->where('id_periode', $request->id_periode);
            });
        }

        $query->orderBy('aduan_count', 'desc');


        // Pagination
        $perPage = $request->input('per_page', 10);
        $pengaduan = $query->paginate($perPage);

        $pengaduan->appends(request()->query());

        // ambil data kategori untuk filter
        $periode = Periode::all();

        if ($request->ajax()) {
            $html = view('sarpras.pengaduan.table', compact('pengaduan', 'pelapor'))->render();
            return response()->json(['html' => $html]);
        }

        return view('sarpras.pengaduan.index', compact('breadcrumb', 'page', 'activeMenu', 'pengaduan', 'periode', 'pelapor'));
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

    public function confirm_penugasan(Request $request, $id)
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
            Aduan::where('id_fasilitas', $id)->where('status', Status::MENUNGGU_DIPROSES->value)
                ->update(['status' => Status::SEDANG_INSPEKSI->value]);
            return redirect()->back()->with('success', 'Berhasil menugaskan inspeksi.');
        } catch (\Exception $e) {
            Log::error('Gagal menugaskan teknisi: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menugaskan teknisi.']);
        }
    }

    private function set_sheet($filter_user)
    {
        $query = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor.role', 'inspeksi'])
            ->whereHas('aduan', function ($query) {
                $query->where('status', Status::MENUNGGU_DIPROSES->value);
            });

        if ($filter_user != 'all') {
            $pelapor = match ($filter_user) {
                '1' => ' dari Mahasiswa',
                '5' => ' dari Dosen',
                '6' => ' dari Tendik',
                default => '' // Opsional: untuk menangani nilai yang tidak sesuai
            };

            // Filter dan count hanya aduan dari pelapor dengan role tertentu
            $query->withCount([
                'aduan as aduan_count' => function ($q) use ($filter_user) {
                    $q->whereHas('pelapor', function ($q2) use ($filter_user) {
                        $q2->where('id_role', $filter_user);
                    });
                }
            ]);

            // Filter hanya fasilitas yang punya aduan dari pelapor dengan role tersebut
            $query->whereHas('aduan', function ($q) use ($filter_user) {
                $q->whereHas('pelapor', function ($q2) use ($filter_user) {
                    $q2->where('id_role', $filter_user);
                });
            });
        } else {
            // Jika tidak difilter berdasarkan role
            $query->withCount('aduan');
        }

        $query->orderBy('aduan_count', 'desc')->get();

        $filename = 'data_pengaduan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $sheet = new Sheet(); // Pass the data and filename to the Sheet
        $sheet->title = 'Data Proses Pengecekan Pengaduan';
        $sheet->text = 'Berikut adalah Data Proses Pengecekan Pengaduan yang masuk' . $pelapor . '.';
        $sheet->footer = 'Dibuat oleh Sistem';
        $sheet->header = ['Kode Fasilitas', 'Nama Fasilitas', 'Kategori', 'Lokasi', 'Urgensi', 'Jumlah Aduan'];

        $sheet->data = $query->map(function ($item) {
            return [
                'nama_fasilitas' => $item->fasilitas->nama_fasilitas,
                'kategori' => $item->fasilitas->kategori->nama_kategori,
                'tanggal_aduan' => $item->tanggal_aduan,
                'tanggal_perbaikan' => $item->fasilitas->inspeksi->first()->perbaikan ? $item->fasilitas->inspeksi->first()->perbaikan->tanggal_selesai : 'Belum diperbaiki',
            ];
        })->toArray();
        $sheet->filename = $filename;

        return $sheet;
    }
    public function export_excel($filter_role)
    {

        return $this->set_sheet($filter_role)->toXls();
    }

    public function export_pdf()
    {
        // return $this->set_sheet()->toPdf();
    }
}
