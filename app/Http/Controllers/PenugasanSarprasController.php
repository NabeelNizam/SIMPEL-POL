<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Http\Helpers\PrometheeHelper;
use App\Models\Aduan;
use App\Models\Biaya;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Kategori;
use App\Models\Kriteria;
use App\Models\Notifikasi;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;

class PenugasanSarprasController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'penugasan';

        $tanggalSekarang = Carbon::now()->day;

        // ini jangan dihapus
        // if($tanggalSekarang <= 15) {
        //     $breadcrumb = (object) [
        //         'title' => '',
        //         'list' => []
        //     ];

        //     $pesan = 'Maaf, Anda tidak dapat mengakses menu ini sebelum tanggal <span class="font-semibold">16</span>';

        //     return view ('access.denied', [
        //         'activeMenu' => $activeMenu,
        //         'breadcrumb' => $breadcrumb,
        //         'pesan' => $pesan,
        //     ]);
        // }

        $breadcrumb = (object) [
            'title' => 'Laporan Penugasan',
            'list' => ['Home', 'Laporan Penugasan']
        ];

        $page = (object) [
            'title' => 'Daftar penugasan yang terdaftar dalam sistem'
        ];

        // Ambil data kriteria
        $kriteria = Kriteria::all()->keyBy('nama_kriteria')->toArray();
        $kriteriaList = [
            'user_count' => $kriteria['User Count'],
            'urgensi_fasilitas' => $kriteria['Urgensi'],
            'biaya' => $kriteria['Biaya Anggaran'],
            'tingkat_kerusakan' => $kriteria['Tingkat Kerusakan'],
            'laporan_berulang' => $kriteria['Laporan Berulang'],
            'bobot_pelapor' => $kriteria['Bobot Pelapor'],
        ];

        // Query untuk Inspeksi (tanpa filter id_periode dan pencarian di sini)
        $query = Inspeksi::with([
            'fasilitas',
            'fasilitas.kategori',
            'fasilitas.ruangan',
            'fasilitas.ruangan.lantai',
            'fasilitas.ruangan.lantai.gedung',
            'periode',
        ])
            ->select('inspeksi.*')
            ->distinct()
            ->whereNotNull('tingkat_kerusakan')
            ->whereNotNull('tanggal_selesai')
            ->whereDoesntHave('perbaikan');

        // Ambil data inspeksi sebagai koleksi Eloquent
        $inspeksiCollection = $query->get();

        // Konversi ke array dan tambahkan user_count, laporan_berulang, dan bobot_pelapor
        $inspeksi = $inspeksiCollection->map(function ($item) {
            $data = $item->toArray();
            $data['user_count'] = $item->user_count;
            $data['laporan_berulang'] = $item->skor_laporan_berulang;
            $data['bobot_pelapor'] = $item->bobot_pelapor;
            return $data;
        })->toArray();

        // Proses PROMETHEE pada semua data
        if (!empty($inspeksi)) {
            $inspeksi = PrometheeHelper::processPromethee($inspeksi, $kriteriaList);
        }

        // Filter berdasarkan id_periode dan pencarian setelah PROMETHEE
        if ($request->id_periode || $request->search) {
            $inspeksi = array_filter($inspeksi, function ($item) use ($request) {
                $periodeMatch = !$request->id_periode || $item['periode']['id_periode'] == $request->id_periode;
                $searchMatch = !$request->search ||
                            (isset($item['fasilitas']['nama_fasilitas']) &&
                                stripos($item['fasilitas']['nama_fasilitas'], $request->search) !== false);
                return $periodeMatch && $searchMatch;
            });
            // Reset array keys agar paginasi bekerja dengan benar
            $inspeksi = array_values($inspeksi);
        }

        // Konversi ke paginator
        $perPage = $request->input('per_page', 10);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $pagedInspeksi = array_slice($inspeksi, $offset, $perPage);
        $penugasan = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedInspeksi,
            count($inspeksi),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil data periode untuk filter
        $periode = Periode::all();

        if ($request->ajax()) {
            $html = view('sarpras.penugasan.penugasan_table', compact('penugasan'))->render();
            return response()->json(['html' => $html]);
        }

        return view('sarpras.penugasan.index', compact('breadcrumb', 'page', 'activeMenu', 'penugasan', 'periode'));
    }

    // Detail Fasilitas & Laporan Pengaduan nya
    public function show_penugasan(Inspeksi $inspeksi)
    {
        $biaya = Biaya::where('id_inspeksi', $inspeksi->id_inspeksi)->get();
        return view('sarpras.penugasan.detail', ['inspeksi' => $inspeksi, 'biaya' => $biaya]);
    }

    // Method menugaskan teknisi untuk inspeksi
    public function penugasan_teknisi(Inspeksi $inspeksi)
    {
        return view('sarpras.penugasan.confirm', ['inspeksi' => $inspeksi]);
    }

    public function store_penugasan(Request $request)
    {
        $request->validate([
            'id_inspeksi' => 'required|integer|exists:inspeksi,id_inspeksi',
        ]);

        $periode = Periode::getPeriodeAktif();

        if (!$periode) {
            return redirect()->back()->withErrors(['periode_id' => 'Periode tidak ditemukan.']);
        }

        try {
            $inspeksi = Inspeksi::findOrFail($request->id_inspeksi);

            // Ambil semua aduan dengan id_fasilitas yang sama dan status SEDANG_INSPEKSI
            $aduan = Aduan::where('id_fasilitas', $inspeksi->id_fasilitas)
                ->where('status', Status::SEDANG_INSPEKSI->value)->get();
            $fasilitas = Fasilitas::where('id_fasilitas', $inspeksi->id_fasilitas)->value('nama_fasilitas');
            foreach ($aduan as $a) {
                $a->update(['status' => Status::SEDANG_DIPERBAIKI->value]);

                // Notifikasi ke pelapor
                Notifikasi::create([
                    'pesan' => 'Fasilitas <b class="text-red-500">' . $fasilitas . '</b> yang Anda laporkan saat ini sedang dalam proses perbaikan oleh teknisi.',
                    'waktu_kirim' => now(),
                    'id_user' => $a->pelapor->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Notifikasi ke teknisi
            Notifikasi::create([
                'pesan' => 'Anda ditugaskan untuk melakukan perbaikan fasilitas <b class="text-red-500">' . $fasilitas . '</b> berdasarkan hasil inspeksi sebelumnya.',
                'waktu_kirim' => now(),
                'id_user' => $inspeksi->id_user_teknisi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui status aduan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal memperbarui status aduan.']);
        }

        try {
            Perbaikan::create([
                'id_inspeksi' => $request->id_inspeksi,
                'id_periode' => $periode->id_periode,
                'tanggal_mulai' => now(),
            ]);
            return redirect()->back()->with('success', 'Berhasil menugaskan teknisi.');
        } catch (\Exception $e) {
            Log::error('Gagal menugaskan teknisi: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menugaskan teknisi.']);
        }
    }
}
