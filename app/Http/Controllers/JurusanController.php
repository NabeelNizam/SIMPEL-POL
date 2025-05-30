<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class JurusanController extends Controller
{
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Manajemen Jurusan',
        'list' => ['Home', 'Manajemen Data Jurusan']
    ];

    $page = (object) [
        'title' => 'Daftar jurusan yang terdaftar dalam sistem'
    ];

    $activeMenu = 'jurusan';

    // Query untuk model jurusan
    $query = Jurusan::query();

    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->where('nama_jurusan', 'like', "%{$request->search}%");
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'nama_jurusan'; // Default sorting by nama_jurusan
    $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $perPage = $request->input('per_page', 10); // default 10
    $jurusan = $query->paginate($perPage);

    $jurusan->appends(request()->query());

    // Jika permintaan adalah AJAX, kembalikan hanya tabel
    if ($request->ajax()) {
        $html = view('admin.jurusan.jurusan_table', compact('jurusan'))->render();
        return response()->json(['html' => $html]);
    }

    // Kembalikan view utama
    return view('admin.jurusan.index', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'activeMenu' => $activeMenu,
        'jurusan' => $jurusan,
    ]);
    }
}