<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
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
    $btn  = '<button onclick="modalAction(\'' . url('/user/' . $user->id_user . '/show_ajax') . '\')" class="text-blue-500 hover:text-blue-700 mx-1" title="Detail">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>';
    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id_user . '/edit_ajax') . '\')" class="text-yellow-500 hover:text-yellow-700 mx-1" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 14h2m-7-7h14m-7-7v14" />
                </svg>
            </button>';
    $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id_user . '/delete_ajax') . '\')" class="text-red-500 hover:text-red-700 mx-1" title="Hapus">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>';
    return $btn;
})
            ->rawColumns(['aksi'])
            ->make(true);
    }




}
