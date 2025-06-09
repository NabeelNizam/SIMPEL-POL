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



}
