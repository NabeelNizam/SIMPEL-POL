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
    public function pengguna()
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

        return view('admin.pengguna.user', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'role' => $role]);
    }


    public function list(Request $request)
    {
        $users = User::select('id_user', 'username', 'nama', 'id_role', 'email')
            ->with('role');

        if ($request->id_role) {
            $users->where('id_role', $request->id_role);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('role', function ($user) {
                return $user->role->nama_role ?? '-';
            })
            ->addColumn('aksi', function ($user) {
                $btn  = '<button onclick="modalAction(\'' . route('admin.show_ajax', $user->id_user) . '\')" class="text-blue-500 hover:text-blue-700 mx-1" title="Detail">
                    <img src="' . asset('icons/solid/Detail.svg') . '" class="h-5 w-5 inline" alt="Detail">
                </button>';

                $btn .= '<button onclick="modalAction(\'' . route('admin.edit_ajax', $user->id_user) . '\')" class="text-yellow-500 hover:text-yellow-700 mx-1" title="Edit">
                        <img src="' . asset('icons/solid/Edit.svg') . '" class="h-5 w-5 inline" alt="Edit">
                    </button>';

                $btn .= '<button onclick="modalAction(\'' . route('admin.delete_ajax', $user->id_user) . '\')" class="text-red-500 hover:text-red-700 mx-1" title="Hapus">
                        <img src="' . asset('icons/solid/Delete.svg') . '" class="h-5 w-5 inline" alt="Hapus">
                    </button>';


                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $role = Role::all();
        $jurusan = Jurusan::all();
        return view('admin.pengguna.create', [
            'role' => $role,
            'jurusan' => $jurusan
        ]);
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
            $rules['identifier'] = 'required|string|max:50|unique:mahasiswa,nim';
        } else {
            $rules['identifier'] = 'required|string|max:50|unique:pegawai,nip';
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
                'foto_profil' => fake()->image()
            ]);

            if ($isMahasiswa) {
                $identifier = Mahasiswa::create([
                    'id_user' => $user->id_user,
                    'nim' => $request->identifier
                ]);
            } else {
                Pegawai::create([
                    'id_user' => $user->id_user,
                    'nip' => $request->identifier
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
