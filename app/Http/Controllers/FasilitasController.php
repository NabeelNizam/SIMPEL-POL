<?php

namespace App\Http\Controllers;

use App\Http\Enums\Kondisi;
use App\Http\Sheet\Sheet;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Lantai;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Periode;
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
    private $queried_fasilitas;
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

        // Ambil data fasilitas yang difilter
        $this->queried_fasilitas = $query;

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
                'kondisi_fasilitas' => 'required|string|in:LAYAK,RUSAK',
                'deskripsi' => 'required|string|min:10|max:255',
                'urgensi' => 'required|string|in:DARURAT,PENTING,BIASA',
                'foto_fasilitas' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $periode = Periode::where('tanggal_mulai', '<=', now())
                        ->where('tanggal_selesai', '>=', now())
                        ->first();

            if (!$periode) {
                return response()->json([
                    'status' => false,
                    'message' => 'Periode tidak ditemukan untuk tanggal saat ini',
                ]);
            }

                // Simpan file foto_fasilitas
            if ($request->hasFile('foto_fasilitas')) {
                $file = $request->file('foto_fasilitas');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/img/foto_fasilitas', $fileName); // Simpan ke storage
                $fotoUrl = 'storage/img/foto_fasilitas/' . $fileName; // URL relatif
            }

            Fasilitas::create([
                'id_ruangan' => $request->ruangan,
                'nama_fasilitas' => $request->nama_fasilitas,
                'id_kategori' => $request->kategori,
                'kondisi' => $request->kondisi_fasilitas,
                'kode_fasilitas' => 'F-' . $request->gedung . $request->lantai . $request->ruangan . '-' . Fasilitas::count() + 1,
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'id_periode' => $periode->id_periode,
                'foto_fasilitas' => $fotoUrl
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data fasilitas berhasil disimpan',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data fasilitas gagal disimpan',
        ]);
    }
    public function export_pdf()
    {
        // Ini untuk mengambil data fasilitas yang sudah difilter (masih gagal)
        // $fasilitas = $this->queried_fasilitas;

        $fasilitas = Fasilitas::with(['kategori', 'ruangan'])->get();

        $headers = ['Kode', 'Nama', 'Kategori', 'Lokasi'];
        $data = $fasilitas->map(function ($item) {
            return [
                'kode' => $item->kode_fasilitas,
                'nama' => $item->nama_fasilitas,
                'kategori' => $item->kategori->nama_kategori,
                'lokasi' => $item->getLokasiString(),
            ];
        })->toArray();
        $sheet = new Sheet(
            [
                'title' => 'Data Fasilitas',
                'text' => 'Berikut adalah daftar fasilitas yang terdaftar di sistem.',
                'footer' => 'Dibuat oleh Nabeela',
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_fasilitas' . date('Y-m-d_H-i-s'),
                'is_landscape' => true, // Mengatur orientasi kertas menjadi landscape
            ]
        );
        return $sheet->toPdf();
    }
    public function export_excel()
    {
        // Ini untuk mengambil data fasilitas yang sudah difilter (masih gagal)
        // $fasilitas = $this->queried_fasilitas;

        $fasilitas = Fasilitas::with(['kategori', 'ruangan'])->get();

        $headers = ['Kode', 'Nama', 'Kategori', 'Lokasi'];
        $data = $fasilitas->map(function ($item) {
            return [
                'kode' => $item->kode_fasilitas,
                'nama' => $item->nama_fasilitas,
                'kategori' => $item->kategori->nama_kategori,
                'lokasi' => $item->getLokasiString(),
            ];
        })->toArray();
        $sheet = new Sheet(
            [
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_fasilitas' . date('Y-m-d_H-i-s'),
            ]
        );
        return $sheet->toXls();
    }
}
