<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Models\Lantai;
use App\Models\Periode;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    public function create()
    {
        $gedung = Gedung::all();
        $lantai = Lantai::all();
        $ruangan = Ruangan::all();
        $fasilitas = Fasilitas::all();
        return view(
            'mahasiswa.form.create',
            [
                'gedung' => $gedung,
                'lantai' => $lantai,
                'ruangan' => $ruangan,
                'fasilitas' => $fasilitas
            ]
        )->render();
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'fasilitas' => ['required', 'exists:fasilitas,id_fasilitas'],
                'deskripsi' => 'required|string|max:255',
            ]);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withErrors($validation)
                    ->withInput();
            }

            $fasilitas = Fasilitas::findOrFail($request->fasilitas);
            $aduan = Aduan::where('id_fasilitas', $request->fasilitas)->where('id_user_pelapor', auth()->user()->id_user)->get();
            // $fasilitas->whereHas('aduan', function ($q) {
            //     $q->where('id_user_pelapor', auth()->user()->id_user)->get();
            // });
            // dd($aduan);

            // if ($aduan->count() == 0) {
            //     return redirect()->back()->withErrors(['fasilitas' => 'Anda sudah membuat aduan untuk fasilitas ini.'])->withInput();
            // }

            $is_selesai = $aduan->where('status', '!=' , 'SELESAI')->count() > 0;

            if ($aduan->count() > 0 && $is_selesai) {
                return redirect()->back()->withErrors(['fasilitas' => 'Anda sudah membuat aduan untuk fasilitas ini.'])->withInput();
            }


            $aduan = Aduan::create([
                'id_user_pelapor' => auth()->user()->id_user,
                'id_fasilitas' => $request->fasilitas,
                'deskripsi' => $request->deskripsi,
                'bukti_foto' => $request->bukti_foto ? $request->bukti_foto->store('aduan', 'public') : null,
                'tanggal_aduan' => now(),
                'id_periode' => Periode::where('tanggal_mulai', '<=', now())
                    ->where('tanggal_selesai', '>=', now())
                    ->value('id_periode'),
                'status' => $this->tentukanStatusFasilitas($request->fasilitas)->value,
            ]);

            // return response()->json(['status' => true, 'message' => 'Aduan berhasil dibuat', 'data' => $aduan]);
            return redirect()->route('mahasiswa.form')->with('success', 'Aduan berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
    public function edit(Aduan $aduan)
    {
        $aduan = Aduan::with('fasilitas.ruangan.lantai.gedung')->findOrFail($aduan->id_aduan);
        $gedung = Gedung::all();
        return view('mahasiswa.form.edit', compact('aduan', 'gedung'));
    }
    public function update(Request $request, Aduan $aduan)
    {
        try {
            $validation = Validator::make($request->all(), [
                'fasilitas' => ['required', 'exists:fasilitas,id_fasilitas'],
                'deskripsi' => 'required|string|max:255',
            ]);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withErrors($validation)
                    ->withInput();
            }
            $aduan->update([
                'id_fasilitas' => $request->fasilitas,
                'deskripsi' => $request->deskripsi,
                'bukti_foto' => $request->bukti_foto ? $request->bukti_foto->store('aduan', 'public') : $aduan->bukti_foto,
            ]);

            return redirect()->route('mahasiswa.form')->with('success', 'Aduan berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
    public function getLantai($id_gedung)
    {
        // $lantai = Lantai::where('id_gedung', $id_gedung)->get();
        $lantai = Lantai::with('gedung')->where('id_gedung', $id_gedung)->get();
        Log::info('Lantai yang diambil: ' .  $lantai);
        return response()->json($lantai);
    }

    // Ambil ruangan berdasarkan lantai
    public function getRuangan($id_lantai)
    {
        $ruangan = Ruangan::where('id_lantai', $id_lantai)->get();
        return response()->json($ruangan);
    }
    public function getFasilitas($id_ruangan)
    {
        $fasilitas = Fasilitas::where('id_ruangan', $id_ruangan)->get();
        return response()->json($fasilitas);
    }
    private function tentukanStatusFasilitas(int $id_fasilitas): Status
    {
        $periode_aktif = Periode::getPeriodeAktif();

        // 1. Cek apakah ada aduan dengan status SEDANG_INSPEKSI
        if (Aduan::where('id_fasilitas', $id_fasilitas)->where('status', Status::SEDANG_INSPEKSI->value)->exists()) {
            return Status::SEDANG_INSPEKSI;
        }

        // 2. Cek apakah ada aduan dengan status SEDANG_DIPERBAIKI
        if (Aduan::where('id_fasilitas', $id_fasilitas)->where('status', Status::SEDANG_DIPERBAIKI->value)->exists()) {
            return Status::SEDANG_DIPERBAIKI;
        }

        // 3. Cek aduan SELESAI
        $aduanSelesai = Aduan::where('id_fasilitas', $id_fasilitas)
            ->where('status', Status::SELESAI)
            ->orderByDesc('id_periode')
            ->first();

        if ($aduanSelesai) {
            if ($aduanSelesai->id_periode < $periode_aktif->id) {
                return Status::MENUNGGU_DIPROSES;
            } elseif ($aduanSelesai->id_periode == $periode_aktif->id) {
                return Status::SELESAI;
            }
        }

        // Jika tidak ada aduan sama sekali, bisa return status default (opsional)
        return Status::MENUNGGU_DIPROSES;
    }
}
