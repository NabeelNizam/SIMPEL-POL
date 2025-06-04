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
<<<<<<< HEAD
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Riwayat Perbaikan',
        'list' => ['Home', 'Riwayat Perbaikan']
    ];
=======
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Perbaikan',
            'list' => ['Home', 'Riwayat Perbaikan']
        ];
>>>>>>> c34c97dfa5ab4df23a2f77077d97e9c722f8f5b6

    $page = (object) [
        'title' => 'Daftar aduan dengan status selesai'
    ];

    $activeMenu = 'laporan-completed';

    // Query untuk aduan dengan status selesai
    $query = Aduan::with(['fasilitas', 'prioritas', 'perbaikan'])->where('status', 'Selesai');

<<<<<<< HEAD
    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->whereHas('fasilitas', function ($q) use ($request) {
            $q->where('nama_fasilitas', 'like', "%{$request->search}%");
        });
=======
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
>>>>>>> c34c97dfa5ab4df23a2f77077d97e9c722f8f5b6
    }

<<<<<<< HEAD
    // Filter berdasarkan kategori
    if ($request->id_kategori) {
        $query->whereHas('fasilitas', function ($q) use ($request) {
            $q->where('id_kategori', $request->id_kategori);
        });
=======
        $aduan = Aduan::query()->where('status', 'menunggu_diproses')
            ->with(['fasilitas', 'perbaikan'])
            ->get();

        $filename = 'riwayat_perbaikan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $sheet = new Sheet(); // Pass the data and filename to the Sheet
        $sheet->title = 'Riwayat Perbaikan';
        $sheet->text = 'Berikut adalah daftar riwayat perbaikan yang telah selesai.';
        $sheet->footer = 'Dibuat oleh Sistem';
        $sheet->header = ['Nama Fasilitas', 'Kategori', 'Tanggal Aduan', 'Tanggal Perbaikan'];

        $sheet->data = $aduan->map(function ($item) {
            return [
                'nama_fasilitas' => $item->fasilitas->nama_fasilitas,
                'kategori' => $item->fasilitas->kategori->nama_kategori,
                'tanggal_aduan' => $item->tanggal_aduan,
                'tanggal_perbaikan' => $item->perbaikan ? $item->perbaikan->tanggal_selesai : 'Belum diperbaiki',
            ];
        })->toArray();
        $sheet->filename = $filename;

        return $sheet;
>>>>>>> c34c97dfa5ab4df23a2f77077d97e9c722f8f5b6
    }

    // Filter berdasarkan prioritas
    if ($request->id_prioritas) {
        $query->where('id_prioritas', $request->id_prioritas);
    }

<<<<<<< HEAD
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
=======

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
    public function export_pdf()
    {
        return $this->set_sheet()->toPdf();
    }
>>>>>>> c34c97dfa5ab4df23a2f77077d97e9c722f8f5b6
}
}