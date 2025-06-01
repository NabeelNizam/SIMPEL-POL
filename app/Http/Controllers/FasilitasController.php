<?php

namespace App\Http\Controllers;

use App\Http\Enums\Kondisi;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Lantai;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

    public function create()
    {
        $gedung = Gedung::all();
        $kategori = Kategori::all();

        return view('admin.fasilitas.create', [
            'gedung' => $gedung,
            'kategori' => $kategori,
        ]);
    }

    // Ambil lantai berdasarkan gedung
    public function getLantai($id_gedung)
    {
        $lantai = Lantai::where('id_gedung', $id_gedung)->get();
        return response()->json($lantai);
    }

    // Ambil ruangan berdasarkan lantai
    public function getRuangan($id_lantai)
    {
        $ruangan = Ruangan::where('id_lantai', $id_lantai)->get();
        return response()->json($ruangan);
    }


    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung' => 'required|integer|exists:gedung,id_gedung',
                'lantai' => 'required|integer|exists:lantai,id_lantai',
                'ruangan' => 'required|integer|exists:ruangan,id_ruangan',
                'nama_fasilitas' => 'required|string|min:2|max:35',
                'kategori' => 'required|integer|exists:kategori,id_kategori',
                'kondisi_fasilitas' => 'required|string|in:BAIK,RUSAK,RUSAK BERAT',
                'deskripsi' => 'required|string|min:10|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            Fasilitas::create([
                'id_ruangan' => $request->ruangan,
                'nama_fasilitas' => $request->nama_fasilitas,
                'id_kategori' => $request->kategori,
                'kondisi' => $request->kondisi_fasilitas,
                'kode_fasilitas' => 'F-' . $request->gedung . $request->lantai . $request->ruangan . '-' . Fasilitas::count() + 1,
                'deskripsi' => $request->deskripsi
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data fasilitas berhasil disimpan',
            ]);
        }
        return redirect('/admin/fasilitas')->with('error', 'Data fasilitas gagal disimpan');
    }
}