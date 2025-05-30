<?php

namespace App\Http\Controllers;

use App\Http\Enums\Kondisi;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class FasilitasController extends Controller
{
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Manajemen Fasilitas',
        'list' => ['Home', 'Manajemen Data Fasilitas']
    ];

    $page = (object) [
        'title' => 'Daftar fasilitas yang terdaftar dalam sistem'
    ];

    $activeMenu = 'fasilitas';


    // Query untuk fasilitas
    $query = Fasilitas::with(['kategori', 'ruangan']);

    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->where('nama_fasilitas', 'like', "%{$request->search}%");
    }

    // Filter berdasarkan kategori
    if ($request->id_kategori) {
        $query->where('id_kategori', $request->id_kategori);
    }

    // Filter berdasarkan gedung
    if ($request->id_gedung) {
        $query->where('id_gedung', $request->id_gedung);
    }

    // Filter berdasarkan kondisi
    if ($request->kondisi) {
        $query->where('kondisi', $request->kondisi);
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'nama_fasilitas';
    $sortDirection = $request->sort_direction ?? 'asc';
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $perPage = $request->input('per_page', 10);
    $fasilitas = $query->paginate($perPage);

    $fasilitas->appends(request()->query());

    // Ambil data kategori dan gedung untuk filter
    $kategori = Kategori::all();
    $gedung = Gedung::all();
    $kondisi = Kondisi::cases();

    if ($request->ajax()) {
        $html = view('admin.fasilitas.fasilitas_table', compact('fasilitas'))->render();
        return response()->json(['html' => $html]);
    }
    

    return view('admin.fasilitas.index', compact('breadcrumb', 'page', 'activeMenu', 'fasilitas', 'kategori', 'gedung', 'kondisi'));
}
}