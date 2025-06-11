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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;

class PenugasanSarprasController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Penugasan',
            'list' => ['Home', 'Laporan Penugasan']
        ];

        $page = (object) [
            'title' => 'Daftar penugasan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penugasan';

        // Ambil data kriteria
        $kriteria = Kriteria::all()->keyBy('nama_kriteria')->toArray();
        $kriteriaList = [
            'user_count' => $kriteria['User Count'],
            'urgensi_fasilitas' => $kriteria['Urgensi'],
            'biaya' => $kriteria['Biaya Anggaran'],
            'tingkat_kerusakan' => $kriteria['Tingkat Kerusakan'],
            'waktu_selesai' => $kriteria['Waktu'],
        ];

        // Query untuk Inspeksi
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

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->whereHas('fasilitas', function ($q) use ($request) {
                $q->where('nama_fasilitas', 'like', "%{$request->search}%");
            });
        }

        // Filter berdasarkan periode
        if ($request->id_periode) {
            $query->whereHas('periode', function ($q) use ($request) {
                $q->where('id_periode', $request->id_periode);
            });
        }

        // Ambil data inspeksi sebagai koleksi Eloquent
        $inspeksiCollection = $query->get();

        // Konversi ke array dan tambahkan user_count
        $inspeksi = $inspeksiCollection->map(function ($item) {
            $data = $item->toArray();
            $data['user_count'] = $item->user_count; // Panggil accessor
            return $data;
        })->toArray();

        // Log untuk memastikan tidak ada duplikasi sebelum PrometheeHelper
        $idInspeksiCounts = array_count_values(array_column($inspeksi, 'id_inspeksi'));
        foreach ($idInspeksiCounts as $id => $count) {
            if ($count > 1) {
                Log::warning("SarprasController: Duplikasi id_inspeksi ditemukan sebelum PrometheeHelper: id_inspeksi $id muncul $count kali", $inspeksi);
            }
        }
        Log::info('SarprasController: Data Inspeksi Sebelum PrometheeHelper', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'user_count_values' => array_map(function ($item) {
                return [
                    'id_inspeksi' => $item['id_inspeksi'],
                    'user_count' => $item['user_count'] ?? null,
                ];
            }, $inspeksi),
        ]);

        // Proses PROMETHEE
        if (!empty($inspeksi)) {
            $inspeksi = PrometheeHelper::processPromethee($inspeksi, $kriteriaList);
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

        // Log hasil penugasan
        Log::info('SarprasController: Data Penugasan Setelah PrometheeHelper', [
            'jumlah_penugasan' => count($penugasan),
            'id_inspeksi' => array_column($penugasan->items(), 'id_inspeksi'),
            'hasil' => array_map(function ($item) {
                return [
                    'id_inspeksi' => $item['id_inspeksi'],
                    'skor' => $item['skor'] ?? null,
                    'ranking' => $item['ranking'] ?? null,
                    'waktu' => $item['waktu'] ?? null,
                ];
            }, $penugasan->items()),
        ]);

        // Periksa duplikasi di penugasan
        $penugasanIds = array_count_values(array_column($penugasan->items(), 'id_inspeksi'));
        foreach ($penugasanIds as $id => $count) {
            if ($count > 1) {
                Log::warning("SarprasController: Duplikasi id_inspeksi ditemukan di penugasan: id_inspeksi $id muncul $count kali", $penugasan->items());
            }
        }

        // Ambil data periode untuk filter
        $periode = Periode::all();

        if ($request->ajax()) {
            $html = view('sarpras.penugasan.penugasan_table', compact('penugasan'))->render();
            return response()->json(['html' => $html]);
        }

        // dd($penugasan);

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
                    'pesan' => 'Fasilitas <b class="text-blue-500">' . $fasilitas . '</b> yang Anda laporkan saat ini sedang dalam proses perbaikan oleh teknisi.',
                    'waktu_kirim' => now(),
                    'id_user' => $a->pelapor->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Notifikasi ke teknisi 
            Notifikasi::create([
                'pesan' => 'Anda ditugaskan untuk melakukan perbaikan fasilitas <b class="text-blue-500">' . $fasilitas . '</b> berdasarkan hasil inspeksi sebelumnya.',
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
