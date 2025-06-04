<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Http\Enums\Kondisi;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Periode;
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

    // Filter berdasarkan periode
    if ($request->id_periode) {
        $query->where('id_periode', $request->id_periode);
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'tanggal_aduan';
    $sortDirection = $request->sort_direction ?? 'asc';
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $perPage = $request->input('per_page', 10);
    $aduan = $query->paginate($perPage);

    $aduan->appends(request()->query());

    // Ambil data kategori, prioritas, dan periode untuk filter
    $kategori = Kategori::all();
    $prioritas = Prioritas::all();
    $periode = Periode::all();

    if ($request->ajax()) {
        $html = view('teknisi.riwayat.riwayat_table', compact('aduan'))->render();
        return response()->json(['html' => $html]);
    }

    return view('teknisi.riwayat.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori', 'prioritas', 'periode'));
}

public function show_ajax($id_fasilitas)
    {
    // Ambil data aduan berdasarkan id_fasilitas
    $aduan = Aduan::with(['fasilitas.ruangan.lantai.gedung', 'prioritas', 'perbaikan.biaya'])->where('id_fasilitas', $id_fasilitas)->firstOrFail();

    // Ambil data perbaikan terkait aduan
    $perbaikan = $aduan->perbaikan;

     $fasilitas = Fasilitas::with('kategori')->findOrFail($id_fasilitas); // Ambil fasilitas beserta kategori
    $kategori = $fasilitas->kategori;

    return view('teknisi.riwayat.detail', compact('aduan', 'perbaikan', 'fasilitas', 'kategori'))->render();
}
}