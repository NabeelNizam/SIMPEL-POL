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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


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

        $query = Fasilitas::with(['kategori', 'ruangan']);

        $this->queried_fasilitas = $query;

        if ($request->search) {
            $query->where('nama_fasilitas', 'like', "%{$request->search}%");
        }

        if ($request->id_kategori) {
            $query->where('id_kategori', $request->id_kategori);
        }

        if ($request->id_gedung) {
            $query->whereHas('ruangan.lantai.gedung', function ($q) use ($request) {
                $q->where('id_gedung', $request->id_gedung);
            });
        }

        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }

        $sortColumn = $request->sort_column ?? 'nama_fasilitas';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortColumn, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $fasilitas = $query->paginate($perPage);

        $fasilitas->appends(request()->query());

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
        $request->validate([
            'gedung' => 'required|integer|exists:gedung,id_gedung',
            'lantai' => 'required|integer|exists:lantai,id_lantai',
            'ruangan' => 'required|integer|exists:ruangan,id_ruangan',
            'nama_fasilitas' => 'required|string|min:2|max:35',
            'kategori' => 'required|integer|exists:kategori,id_kategori',
            'kondisi' => 'required|string|in:LAYAK,RUSAK',
            'deskripsi' => 'required|string|min:10|max:255',
            'urgensi' => 'required|string|in:DARURAT,PENTING,BIASA',
            'foto_fasilitas' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $periode = Periode::where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->first();

        if (!$periode) {
            return redirect()->back()->withErrors(['periode_id' => 'Periode tidak ditemukan.']);
        }

        $fileName = null;
        if ($request->hasFile('foto_fasilitas')) {
            try {
                $file = $request->file('foto_fasilitas');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/img/foto_fasilitas', $fileName, 'public');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['foto_fasilitas' => 'Gagal menyimpan foto fasilitas.']);
            }
        }

        try {
            Fasilitas::create([
                'id_ruangan' => $request->ruangan,
                'nama_fasilitas' => $request->nama_fasilitas,
                'id_kategori' => $request->kategori,
                'kondisi' => $request->kondisi,
                'kode_fasilitas' => 'F-' . $request->gedung . $request->lantai . $request->ruangan . '-' . fake()->unique()->numberBetween(0, 9999),
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'id_periode' => $periode->id_periode,
                'foto_fasilitas' => $fileName
            ]);
            return redirect()->back()->with('success', 'Data fasilitas berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan fasilitas: '.$e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menyimpan data.']);
        }
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
            $fileName = $fasilitas->foto_fasilitas ?? null;

            $fasilitas->delete();

            if($fileName) {
                Storage::disk('public')->delete('uploads/img/foto_fasilitas/' . $fileName);
            }

            return redirect()->back()->with('success', 'Data fasilitas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Gagal menghapus data fasilitas.']);
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
        $rules = [
            'file_input' => ['required', 'mimes:xlsx', 'max:1024']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
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
            return redirect()->back()->withErrors(['periode_id' => 'Periode tidak ditemukan.']);
        }

        $file = $request->file('file_input');

        $reader = IOFactory::createReader('Xlsx'); 
        $reader->setReadDataOnly(true); 
        $spreadsheet = $reader->load($file->getRealPath()); 
        $sheet = $spreadsheet->getActiveSheet();

        $data = $sheet->toArray(null, false, true, true);

        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) { 
                    $insert[] = [
                        'kode_fasilitas' => $value['A'],
                        'nama_fasilitas' => $value['B'],
                        'id_kategori' => Kategori::where('kode_kategori', $value['C'])->first()->id_kategori,
                        'id_ruangan' => Ruangan::where('kode_ruangan', $value['D'])->first()->id_ruangan,
                        'urgensi' => strtoupper($value['E']),
                        'kondisi' => strtoupper($value['F']),
                        'deskripsi' => $value['G'],
                        'foto_fasilitas' => '0',
                        'id_periode' => $periode->id_periode,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            

            if (count($insert) > 0) {
                Fasilitas::insertOrIgnore($insert);
            }

            return redirect()->back()->with('success', 'Data berhasil diimport.');
        } else {
            return redirect()->back()->withErrors(['general' => 'Data gagal diimport.']);
        }
    }

    public function export_pdf()
    {
        // Ini untuk mengambil data fasilitas yang sudah difilter (masih gagal)
        // $fasilitas = $this->queried_fasilitas;

        $fasilitas = Fasilitas::with(['kategori', 'ruangan'])->get();

        $headers = ['Kode', 'Nama', 'Kategori', 'Lokasi', 'Kondisi', 'Urgensi'];
        $data = $fasilitas->map(function ($item) {
            $kondisi = Str::ucfirst(Str::lower($item->kondisi->value));
            $urgensi = Str::ucfirst(Str::lower($item->urgensi->value));
            return [
                'kode' => $item->kode_fasilitas,
                'nama' => $item->nama_fasilitas,
                'kategori' => $item->kategori->nama_kategori,
                'lokasi' => $item->getLokasiString(),
                'kondisi' => $kondisi,
                'urgensi' => $urgensi,
            ];
        })->toArray();
        $sheet = Sheet::make(
        [
                'title' => 'Data Fasilitas',
                'text' => 'Berikut adalah daftar fasilitas yang terdaftar di sistem.',
                'footer' => 'Dibuat oleh Nabeela',
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_fasilitas' . date('Y-m-d_H-i-s'),
                'is_landscape' => false, // Mengatur orientasi kertas menjadi landscape
            ]
        );
        return $sheet->toPdf();
    }
    public function export_excel()
    {
        // Ini untuk mengambil data fasilitas yang sudah difilter (masih gagal)
        // $fasilitas = $this->queried_fasilitas;

        $fasilitas = Fasilitas::with(['kategori', 'ruangan'])->get();

        $headers = ['Kode', 'Nama', 'Kategori', 'Lokasi', 'Kondisi', 'Urgensi'];
        $data = $fasilitas->map(function ($item) {
            $kondisi = Str::ucfirst(Str::lower($item->kondisi->value));
            $urgensi = Str::ucfirst(Str::lower($item->urgensi->value));
            return [
                'kode' => $item->kode_fasilitas,
                'nama' => $item->nama_fasilitas,
                'kategori' => $item->kategori->nama_kategori,
                'lokasi' => $item->getLokasiString(),
                'kondisi' => $kondisi,
                'urgensi' => $urgensi,
            ];
        })->toArray();
        $sheet = Sheet::make(
            [
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_fasilitas' . date('Y-m-d_H-i-s'),
            ]
        );
        return $sheet->toXls();
    }
}
