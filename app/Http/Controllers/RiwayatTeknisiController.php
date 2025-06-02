<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Http\Enums\Kondisi;
use App\Models\Aduan;
use App\Models\Prioritas;
use Illuminate\Http\Request;

class RiwayatTeknisiController extends Controller
{
    public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Riwayat Perbaikan',
        'list' => ['Home', 'Riwayat Perbaikan']
    ];

    $page = (object) [
        'title' => 'Daftar aduan dengan status selesai'
    ];

    $activeMenu = 'laporan-completed';

    // Query untuk aduan dengan status selesai
    $query = Aduan::with(['fasilitas', 'prioritas', 'perbaikan'])->where('status', 'Selesai');

    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->whereHas('fasilitas', function ($q) use ($request) {
            $q->where('nama_fasilitas', 'like', "%{$request->search}%");
        });
    }

    // Filter berdasarkan kategori
    if ($request->id_kategori) {
        $query->whereHas('fasilitas', function ($q) use ($request) {
            $q->where('id_kategori', $request->id_kategori);
        });
    }

    // Filter berdasarkan prioritas
    if ($request->id_prioritas) {
        $query->where('id_prioritas', $request->id_prioritas);
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'tanggal_aduan';
    $sortDirection = $request->sort_direction ?? 'asc';
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $perPage = $request->input('per_page', 10);
    $aduan = $query->paginate($perPage);

    $aduan->appends(request()->query());

    // Ambil data kategori dan prioritas untuk filter
    $kategori = Kategori::all();
    $prioritas = Prioritas::all();

    if ($request->ajax()) {
        $html = view('teknisi.riwayat.riwayat_table', compact('aduan'))->render();
        return response()->json(['html' => $html]);
    }

    return view('teknisi.riwayat.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori', 'prioritas'));
}
}