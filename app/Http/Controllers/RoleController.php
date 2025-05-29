<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Manajemen Role',
        'list' => ['Home', 'Manajemen Data Role']
    ];

    $page = (object) [
        'title' => 'Daftar role yang terdaftar dalam sistem'
    ];

    $activeMenu = 'role';

    // Query untuk model Role
    $query = Role::query();

    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->where('nama_role', 'like', "%{$request->search}%");
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'nama_role'; // Default sorting by nama_role
    $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $perPage = $request->input('per_page', 10); // default 10
    $roles = $query->paginate($perPage);

    $roles->appends(request()->query());

    // Jika permintaan adalah AJAX, kembalikan hanya tabel
    if ($request->ajax()) {
        $html = view('admin.role.role_table', compact('roles'))->render();
        return response()->json(['html' => $html]);
    }

    // Kembalikan view utama
    return view('admin.role.index', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'activeMenu' => $activeMenu,
        'roles' => $roles,
    ]);
}

}