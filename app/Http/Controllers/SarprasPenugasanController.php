<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Models\Aduan;
use App\Models\Kriteria;
use App\Models\Periode;
use Illuminate\Http\Request;

class SarprasPenugasanController extends Controller
{
    public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Penugasan',
        'list' => ['Home', 'Manajemen Data Fasilitas']
    ];

    $page = (object) [
        'title' => 'Daftar penugasan yang terdaftar dalam sistem'
    ];

    $activeMenu = 'penugasan';

    $periode = Periode::all();
    $kriteria = Kriteria::whereIn('id_kriteria', [3, 4, 5])->pluck('bobot', 'id_kriteria');

$query = Aduan::selectRaw('id_fasilitas, id_periode, id_perbaikan, COUNT(*) as jumlah_pelapor')
        ->with(['fasilitas.kategori', 'fasilitas.ruangan.lantai.gedung', 'perbaikan'])
        ->where('status', Status::SEDANG_INSPEKSI->value) // Status adalah SEDANG_INSPEKSI
        ->whereHas('perbaikan', function ($q) {
            $q->whereNotNull('tingkat_kerusakan') // Sudah memiliki tingkat kerusakan
              ->whereNotNull('tanggal_inspeksi'); // Sudah memiliki tanggal inspeksi
        })
        ->groupBy(['id_fasilitas', 'id_periode', 'id_perbaikan']); // Grup berdasarkan id_fasilitas, id_periode, dan id_perbaikan

    // Filter berdasarkan periode
    if ($request->id_periode) {
        $query->where('id_periode', $request->id_periode);
    }

    // Pagination
    $aduan = $query->paginate(10);
    $aduan->appends($request->query());

    // Ambil data periode untuk filter
    $periode = Periode::all();
    $aduan = $query->get();

    
    // Hitung PROMETHEE
    $prometheeResults = $this->calculatePromethee($aduan, $kriteria);

    return view('sarpras.penugasan.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'prometheeResults', 'periode', 'kriteria'));
}



private function calculatePromethee($data, $weights)
{
    $scores = [];

    foreach ($data as $item) {
        $score = 0;

        // Validasi data perbaikan
        if (!$item->perbaikan || !$item->fasilitas) {
            continue; // Skip jika data tidak valid
        }

        // Konversi tingkat kerusakan ke angka
        $tingkatKerusakan = $item->perbaikan->tingkat_kerusakan instanceof \App\Http\Enums\TingkatKerusakan
            ? $item->perbaikan->tingkat_kerusakan->toNumeric()
            : 0;

        // Konversi tanggal inspeksi ke timestamp
        $tanggalInspeksi = $item->perbaikan->tanggal_inspeksi
            ? strtotime($item->perbaikan->tanggal_inspeksi)
            : 0;

        // Hitung total biaya
        $totalBiaya = $item->perbaikan->biaya->sum('besaran');

        // Hitung skor berdasarkan kriteria dan bobot
        $score += $tingkatKerusakan * (float) ($weights['TKR'] ?? 0); // Kriteria Tingkat Kerusakan
        $score += $tanggalInspeksi * (float) ($weights['WKT'] ?? 0); // Kriteria Waktu
        $score += $totalBiaya * (float) ($weights['BYA'] ?? 0); // Kriteria Biaya
        $score += $item->fasilitas->anggaran * (float) ($weights['ANG'] ?? 0); // Kriteria Anggaran

        // Simpan skor dan detail perhitungan
        $scores[] = [
            'id' => $item->id,
            'nama_fasilitas' => $item->fasilitas->nama_fasilitas,
            'score' => $score,
            'detail' => [
                'tingkat_kerusakan' => $tingkatKerusakan,
                'tanggal_inspeksi' => $item->perbaikan->tanggal_inspeksi,
                'biaya' => $totalBiaya,
                'anggaran' => $item->fasilitas->anggaran,
                'weighted_score' => [
                    'tingkat_kerusakan' => $tingkatKerusakan * (float) ($weights['TKR'] ?? 0),
                    'waktu' => $tanggalInspeksi * (float) ($weights['WKT'] ?? 0),
                    'biaya' => $totalBiaya * (float) ($weights['BYA'] ?? 0),
                    'anggaran' => $item->fasilitas->anggaran * (float) ($weights['ANG'] ?? 0),
                ]
            ]
        ];
    }

    // Urutkan berdasarkan skor (prioritas tertinggi di atas)
    usort($scores, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    return $scores;
}
    
}
