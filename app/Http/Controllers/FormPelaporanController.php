<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Models\Lantai;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class FormPelaporanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Form Pelaporan',
            'list' => ['Home', 'Form Pelaporan']
        ];

        $page = (object) [
            'title' => 'Form untuk melaporkan aduan'
        ];

        $activeMenu = 'form';

        // query untuk aduan
        $query = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan'])
            ->where('id_user_pelapor', auth()->user()->id_user)
            ->where('status', '!=', 'SELESAI'); // Hanya ambil aduan yang belum selesai

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

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
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
        $status = Status::cases();

        if ($request->ajax()) {
            $html = view('mahasiswa.form.form_table', compact('aduan', 'kategori'))->render();
            return response()->json(['html' => $html]);
        }

        return view('mahasiswa.form.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori', 'status'));
    }

    public function show_ajax($id)
    {
        $aduan = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan.lantai.gedung'])
            ->where('id_user_pelapor', auth()->user()->id_user)->findOrFail($id);
        $user = $aduan->pelapor; 
        return view('mahasiswa.form.detail', compact('aduan', 'user'))->render();
    }
    public function create_ajax()
    {
        $gedung = Gedung::all();
        $lantai = Lantai::all();
        $ruangan = Ruangan::all();
        $fasilitas = Fasilitas::all();
        return view('mahasiswa.form.create', ['gedung' => $gedung, 'lantai' => $lantai, 'ruangan' => $ruangan, 'fasilitas' => $fasilitas])->render();
    }
    public function getLantai(Request $request)
{
    $lantai = Lantai::where('id_gedung', $request->gedung_id)->get();
    $options = '<option value="">Pilih lantai</option>';
    foreach ($lantai as $l) {
        $options .= '<option value="' . $l->id_lantai . '">' . $l->nama_lantai . '</option>';
    }
    return response()->json(['status' => true, 'options' => $options]);
}

public function getRuangan(Request $request)
{
    $ruangan = Ruangan::where('id_lantai', $request->lantai_id)->get();
    $options = '<option value="">Pilih ruangan</option>';
    foreach ($ruangan as $r) {
        $options .= '<option value="' . $r->id_ruangan . '">' . $r->nama_ruangan . '</option>';
    }
    return response()->json(['status' => true, 'options' => $options]);
}
public function getFasilitas(Request $request)
{
    $fasilitas = Fasilitas::where('id_ruangan', $request->ruangan_id)->get();
    $options = '<option value="">Pilih fasilitas</option>';
    foreach ($fasilitas as $f) {
        $options .= '<option value="' . $f->id_fasilitas . '">' . $f->nama_fasilitas . '</option>';
    }
    return response()->json(['status' => true, 'options' => $options]);
}

}
