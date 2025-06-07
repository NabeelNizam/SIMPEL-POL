<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Kategori;
use App\Models\UmpanBalik;
use Illuminate\Http\Request;

class AduanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Aduan',
            'list' => ['Home', 'Manajemen Aduan']
        ];

        $page = (object) [
            'title' => 'Daftar aduan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'aduan';

        // Query untuk aduan
        $query = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan', 'umpan_balik', 'perbaikan']);

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

        // Sorting
        $sortColumn = $request->sort_column ?? 'tanggal_aduan';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 10);
        $aduan = $query->paginate($perPage);

        $aduan->appends(request()->query());

        // ambil data kategori untuk filter
        $kategori = Kategori::all();

        if ($request->ajax()) {
            $html = view('admin.aduan.aduan_table', compact('aduan'))->render();
            return response()->json(['html' => $html]);
        }
        return view('admin.aduan.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori'));
    }

    public function show_ajax($id)
    {
        $aduan = Aduan::with([
            'pelapor',
            'fasilitas.kategori',
            'fasilitas.ruangan.lantai.gedung',
            'umpan_balik',
            'perbaikan.teknisi.pegawai',
            'perbaikan.biaya'
        ])->findOrFail($id);
        $user = $aduan->pelapor;

        // Ambil rata-rata rating untuk fasilitas & tanggal aduan yang sama
        $avgRating = UmpanBalik::whereHas('aduan', function ($q) use ($aduan) {
            $q->where('id_fasilitas', $aduan->id_fasilitas)
                ->where('tanggal_aduan', $aduan->tanggal_aduan);
        })
            ->avg('rating');

        $avgRating = $avgRating ? number_format($avgRating, 1) : null;

        return view('admin.aduan.detail', compact('aduan', 'user', 'avgRating'))->render();
    }

    public function comment_ajax($id)
    {
        $aduan = Aduan::with(['umpan_balik', 'pelapor'])->findOrFail($id);
        $aduanFasilitas = Aduan::with('pelapor')
            ->where('id_fasilitas', $aduan->id_fasilitas)
            ->where('tanggal_aduan', $aduan->tanggal_aduan)
            ->get();

        $pelaporFasilitas = $aduanFasilitas->pluck('pelapor')->unique('id_user')->filter();

        return view('admin.aduan.comment', [
            'aduan' => $aduan,
            'pelaporFasilitas' => $pelaporFasilitas
        ])->render();
    }

}
