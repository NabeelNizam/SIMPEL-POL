<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GedungController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Data Gedung',
            'list' => ['Home', 'Gedung']
        ];

        $page = (object) [
            'title' => 'Daftar Gedung yang terdaftar dalam sistem'
        ];

        $activeMenu = 'lokasi';


        return view('admin.gedung.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $gedung = Jurusan::select('id_jurusan', 'kode_jurusan', 'nama_jurusan');

        if ($request->id_jurusan) {
            $gedung->where('id_jurusan', $request->id_jurusan);
        }

        return DataTables::of($gedung)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($gedung) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->id_gedung .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->id_gedung .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->id_gedung .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function profil(){
        return view('profil');
    }
}
