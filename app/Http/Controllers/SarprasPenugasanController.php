<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Http\Helpers\AlternativeDTO;
use App\Http\Helpers\PrometheeCalculator2;
use App\Models\Aduan;
use App\Models\Kriteria;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    

   
    
    return view('sarpras.penugasan.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'periode', 'kriteria'));
}


public function calculatePromethee(Request $request)
{
    // Ambil data dari query
    $query = Aduan::selectRaw('id_fasilitas, id_periode, id_perbaikan, COUNT(*) as jumlah_pelapor')
        ->with(['fasilitas.kategori', 'fasilitas.ruangan.lantai.gedung', 'perbaikan.biaya'])
        ->where('status', Status::SEDANG_INSPEKSI->value)
        ->whereHas('perbaikan', function ($q) {
            $q->whereNotNull('tingkat_kerusakan')
              ->whereNotNull('tanggal_inspeksi');
        })
        ->groupBy(['id_fasilitas', 'id_periode', 'id_perbaikan']);

    // Filter berdasarkan periode
    if ($request->id_periode) {
        $query->where('id_periode', $request->id_periode);
    }

    $data = $query->get();
   

    // Validasi data
    if ($data->isEmpty()) {
        return response()->json(['message' => 'No data available for PROMETHEE calculation.'], 404);
    }

    // Hitung PROMETHEE
    $weights = [
        'biaya' => 0.4,
        'tingkat_kerusakan' => 0.4,
        'waktu' => 0.2,
    ];

    $alternatives = [];
    foreach ($data as $item) {
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

        // Tambahkan alternatif ke array
        $alternatives[] = new AlternativeDTO(
            name: $item->fasilitas->nama_fasilitas,
            criteria: [
                'biaya' => $totalBiaya,
                'tingkat_kerusakan' => $tingkatKerusakan,
                'waktu' => $tanggalInspeksi,
            ]
        );
    }

    // Hitung PROMETHEE menggunakan PrometheeCalculator2
    $calculator = new PrometheeCalculator2();
    $rankedAlternatives = $calculator->calculatePromethee2($alternatives, $weights);

    // Format hasil sebagai JSON
    $finalResults = [];
    foreach ($rankedAlternatives as $rank => $alternative) {
        $finalResults[] = [
            'rank' => $rank + 1,
            'name' => $alternative->name,
            'criteria' => $alternative->criteria,
            'score' => $alternative->score,
        ];
    }

    // Return hasil sebagai JSON
    return response()->json($finalResults);
}
}
