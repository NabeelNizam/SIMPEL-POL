<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Kategori;
use Illuminate\Http\Request;

class RiwayatMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Pelaporan',
            'list' => ['Home', 'Riwayat Pelaporan']
        ];

        $page = (object) [
            'title' => 'Daftar Riwayat Aduan'
        ];

        $activeMenu = 'riwayat';

        // query untuk aduan
        $query = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan', 'umpan_balik', 'perbaikan'])
            ->where('id_user_pelapor', auth()->user()->id_user)
            ->where('status', '=', 'SELESAI'); // Hanya ambil aduan selesai

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
            $html = view('mahasiswa.riwayat.riwayat_table', compact('aduan', 'kategori'))->render();
            return response()->json(['html' => $html]);
        }

        return view('mahasiswa.riwayat.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori'));
    }

    public function show_ajax($id)
    {
        $aduan = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan.lantai.gedung', 'umpan_balik', 'perbaikan'])
            ->where('id_user_pelapor', auth()->user()->id_user)->findOrFail($id);
        $user = $aduan->pelapor; 
        return view('mahasiswa.riwayat.detail', compact('aduan', 'user'))->render();
    }

}
