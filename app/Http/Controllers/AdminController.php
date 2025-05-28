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


class AdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard Admin',
            'list' => ['Home', 'dashboard']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dashboard';


        return view('admin.dashboard', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
public function pengguna(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Manajemen Pengguna',
        'list' => ['Home', 'Manajemen Data Pengguna']
    ];

    $page = (object) [
        'title' => 'Daftar pengguna yang terdaftar dalam sistem'
    ];

    $activeMenu = 'pengguna';

    $role = Role::all();
    $query = User::with('role');

    // Filter berdasarkan role
    if ($request->id_role) {
        $query->where('id_role', $request->id_role);
    }

    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'nama'; // Default sorting by nama
    $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
    $query->orderBy($sortColumn, $sortDirection);

    $perPage = $request->input('per_page', 10); // default 10
    $users = $query->paginate($perPage);

    $users->appends(request()->query());
    

    if ($request->ajax()) {
    $html = view('admin.pengguna.user_table', compact('users'))->render();
    return response()->json(['html' => $html]);
}

    return view('admin.pengguna.user', [
    'breadcrumb' => $breadcrumb,
    'page' => $page,
    'activeMenu' => $activeMenu,
    'role' => $role,
    'users' => $users,
]);

}
    public function create_ajax(){
        $role = Role::all();
        $jurusan = Jurusan::all();
        return view('admin.pengguna.create', [
        'role' => $role,
        'jurusan' => $jurusan
    ]);
    }
    public function edit_ajax($id){
        $role = Role::all();
         $user = User::findOrFail($id);
        $jurusan = Jurusan::all();
        return view('admin.pengguna.edit', [
        'role' => $role,
        'jurusan' => $jurusan, 'user' => $user
    ]);
    }
    public function show_ajax($id)
{
    $role = Role::all();
         $user = User::findOrFail($id);
        $jurusan = Jurusan::all();
        return view('admin.pengguna.detail', [
        'role' => $role,
        'jurusan' => $jurusan, 'user' => $user]);
}
    public function import_ajax(){
        return view('admin.pengguna.import');
    }

   public function store_ajax(Request $request)
{
    // Validasi input umum
    $rules = [
        'nama' => 'required|string|max:255',
        'telepon' => 'required|string|max:15',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|string|max:50|unique:users,username',
        'jurusan' => 'required|integer|exists:jurusan,id_jurusan',
        'id_role' => 'required|integer|exists:roles,id_role',
        'password' => 'required|string|min:6',
    ];

    // Cek apakah role Mahasiswa
    $isMahasiswa = strtolower(Role::find($request->id_role)->nama_role) === 'mahasiswa';

    // Tambahkan validasi nim atau nip sesuai role
    if ($isMahasiswa) {
        $rules['nim'] = 'required|string|max:50|unique:mahasiswa,nim';
    } else {
        $rules['nip'] = 'required|string|max:50|unique:pegawai,nip';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Simpan user
        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->telepon,
            'id_jurusan' => $request->jurusan,
            'id_role' => $request->id_role,
            'foto_profil' => 'default.png'
        ]);

        // Simpan ke tabel sesuai role
        if ($isMahasiswa) {
            Mahasiswa::create([
                'id_user' => $user->id_user,
                'nim'     => $request->nim
            ]);
        } else {
            Pegawai::create([
                'id_user' => $user->id_user,
                'nip'     => $request->nip
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


public function remove_ajax($id)
{
    try {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}






}
