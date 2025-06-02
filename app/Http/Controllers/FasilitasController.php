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
use App\Models\Periode;
use App\Models\Role;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
            $query->whereHas('ruangan.lantai.gedung', function ($q) use ($request) {
                $q->where('id_gedung', $request->id_gedung);
            });
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
                'kondisi' => 'required|string|in:LAYAK,RUSAK',
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
                
                $file->storeAs('uploads/img/foto_fasilitas', $fileName, 'public');
            }

            Fasilitas::create([
                'id_ruangan' => $request->ruangan,
                'nama_fasilitas' => $request->nama_fasilitas,
                'id_kategori' => $request->kategori,
                'kondisi' => $request->kondisi,
                'kode_fasilitas' => 'F-' . $request->gedung . $request->lantai . $request->ruangan . '-' . Fasilitas::count() + 1,
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'id_periode' => $periode->id_periode,
                'foto_fasilitas' => $fileName
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

    public function confirm(Fasilitas $fasilitas)
    {
        return view('admin.fasilitas.confirm')->with([
            'fasilitas' => $fasilitas
        ]);
    }

    public function destroy(Fasilitas $fasilitas)
    {
        try {
            if ($fasilitas->foto_fasilitas) {
                Storage::disk('public')->delete('uploads/img/foto_fasilitas/' . $fasilitas->foto_fasilitas);
            }

            $fasilitas->delete();
            return response()->json([
                'status' => true,
                'message' => 'Fasilitas berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Fasilitas $fasilitas)
    {
        $fasilitas = Fasilitas::findOrFail($fasilitas->id_fasilitas);
        return view('admin.fasilitas.detail', ['fasilitas' => $fasilitas]);
    }


    public function edit(Fasilitas $fasilitas)
    {
        $fasilitas = Fasilitas::findOrFail($fasilitas->id_fasilitas);

        $gedung = Gedung::all();
        $kategori = Kategori::all();
        $lantai = Lantai::where('id_gedung', $fasilitas->id_gedung)->get();
        $ruangan = Ruangan::where('id_lantai', $fasilitas->id_lantai)->get();

        return view('admin.fasilitas.edit', compact('fasilitas', 'gedung', 'kategori', 'lantai', 'ruangan'));
    }

    public function update(Request $request, Fasilitas $fasilitas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung' => 'required|integer|exists:gedung,id_gedung',
                'lantai' => 'required|integer|exists:lantai,id_lantai',
                'ruangan' => 'required|integer|exists:ruangan,id_ruangan',
                'nama_fasilitas' => 'required|string|min:2|max:35',
                'kategori' => 'required|integer|exists:kategori,id_kategori',
                'kondisi' => 'required|string|in:LAYAK,RUSAK',
                'deskripsi' => 'required|string|min:10|max:255',
                'urgensi' => 'required|string|in:DARURAT,PENTING,BIASA',
                'foto_fasilitas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $fasilitas = Fasilitas::find($fasilitas->id_fasilitas);
            if (!$fasilitas) {
                return response()->json([
                    'status' => false,
                    'message' => 'Fasilitas tidak ditemukan',
                ]);
            }

            $fileName = $fasilitas->foto_fasilitas;
            if ($request->hasFile('foto_fasilitas')) {
                if ($fileName && Storage::disk('public')->exists('uploads/img/foto_fasilitas/' . $fileName)) {
                    Storage::disk('public')->delete('uploads/img/foto_fasilitas/' . $fileName);
                }
                $file = $request->file('foto_fasilitas');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/img/foto_fasilitas', $fileName, 'public');
            }

            $fasilitas->update([
                'id_ruangan' => $request->ruangan,
                'nama_fasilitas' => $request->nama_fasilitas,
                'id_kategori' => $request->kategori,
                'kondisi' => $request->kondisi,
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'foto_fasilitas' => $fileName
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data fasilitas berhasil diperbarui',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data fasilitas gagal diperbarui',
        ]);
    }

    public function import()
    {
        return view('admin.fasilitas.import');
    }

    public function import_file(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_input' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
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

            $file = $request->file('file_input'); 
 
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel 
            $reader->setReadDataOnly(true); // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true); // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                        $insert[] = [ 
                            'nama_fasilitas' => $value['A'], 
                            'kode_fasilitas' => $value['B'], 
                            'id_kategori' => $value['C'], 
                            'id_ruangan' => $value['D'], 
                            'urgensi' => $value['E'], 
                            'kondisi' => $value['F'], 
                            'deskripsi' => $value['G'], 
                            'foto_fasiltas' => '0',
                            'id_periode' => $periode->id_periode,
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    Fasilitas::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            } else { 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/'); 
    }
}