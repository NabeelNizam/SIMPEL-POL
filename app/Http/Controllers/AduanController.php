<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Kategori;
use App\Models\Perbaikan;
use App\Models\UmpanBalik;
use App\Models\User;
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
        $aduan = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan.lantai.gedung', 'umpan_balik', 'perbaikan.teknisi.pegawai', 'perbaikan.biaya'])->findOrFail($id);
        $user = $aduan->pelapor; 
        return view('admin.aduan.detail', compact('aduan', 'user'))->render();
    }
}
