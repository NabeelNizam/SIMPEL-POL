<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Enums\Kondisi;
use App\Http\Sheet\Sheet;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Lantai;
use App\Models\Periode;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class LokasiController extends Controller
{
    //

    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Data Lokasi Kerusakan Fasilitas',
            'list' => ['Home', 'Lokasi']
        ];

        $page = (object) [
            'title' => 'Daftar Lokasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'lokasi';

        $query = Gedung::with(['lantai.ruangan']); // <- masih Query Builder, belum di get

        // Search & filter
        if ($request->search) {
            $query->where('nama_gedung', 'like', "%{$request->search}%");
        }

        if ($request->id_jurusan) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        $perPage = $request->input('per_page', 10);
        $lokasi = $query->paginate($perPage);

        // Hitung total ruangan per gedung (dalam Collection hasil paginate)
        foreach ($lokasi as $gedung) {
            $gedung->jumlah_lantai = $gedung->lantai->count();
            $gedung->jumlah_ruangan = $gedung->lantai->flatMap->ruangan->count();
        }

        $lokasi->appends(request()->query());

        if ($request->ajax()) {
            $html = view('admin.lokasi.lokasi_table', compact('lokasi'))->render();
            return response()->json(['html' => $html]);
        }


        return view('admin.lokasi.index', compact('breadcrumb', 'page', 'activeMenu', 'lokasi'));
    }

    public function create()
    {
        $jurusan = jurusan::all();

        return view('admin.lokasi.create', [
            'jurusan' => $jurusan,
        ]);
    }

    // Ambil lantai berdasarkan gedung
    // public function getLantai($id_gedung)
    // {
    //     $lantai = Lantai::where('id_gedung', $id_gedung)->get();
    //     return response()->json($lantai);
    // }

    // Ambil ruangan berdasarkan lantai
    // public function getRuangan($id_lantai)
    // {
    //     $ruangan = Ruangan::where('id_lantai', $id_lantai)->get();
    //     return response()->json($ruangan);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'gedung' => 'required|integer|exists:gedung,id_gedung',
            'lantai' => 'required|integer|exists:lantai,id_lantai',
            'ruangan' => 'required|integer|exists:ruangan,id_ruangan',
            'nama_gedung' => 'required|string|min:2|max:35',
            'jurusan' => 'required|integer|exists:jurusan,id_jurusan',
            'kondisi' => 'required|string|in:LAYAK,RUSAK',
            'deskripsi' => 'required|string|min:10|max:255',
            'urgensi' => 'required|string|in:DARURAT,PENTING,BIASA',
            'foto_gedung' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $periode = Periode::where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->first();

        if (!$periode) {
            return redirect()->back()->withErrors(['periode_id' => 'Periode tidak ditemukan.']);
        }

        $fileName = null;
        if ($request->hasFile('foto_gedung')) {
            try {
                $file = $request->file('foto_gedung');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/img/foto_gedung', $fileName, 'public');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['foto_gedung' => 'Gagal menyimpan foto gedung.']);
            }
        }

        try {
            gedung::create([
                'id_ruangan' => $request->ruangan,
                'nama_gedung' => $request->nama_gedung,
                'id_jurusan' => $request->jurusan,
                'kondisi' => $request->kondisi,
                'kode_gedung' => 'F-' . $request->gedung . $request->lantai . $request->ruangan . '-' . fake()->unique()->numberBetween(0, 9999),
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'id_periode' => $periode->id_periode,
                'foto_gedung' => $fileName
            ]);
            return redirect()->back()->with('success', 'Data gedung berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan gedung: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menyimpan data.']);
        }
    }

    public function confirm(gedung $gedung)
    {
        return view('admin.lokasi.confirm')->with([
            'gedung' => $gedung
        ]);
    }

    public function destroy(gedung $gedung)
    {
        try {
            if ($gedung->foto_gedung) {
                Storage::disk('public')->delete('uploads/img/foto_gedung/' . $gedung->foto_gedung);
            }

            $gedung->delete();
            return response()->json([
                'status' => true,
                'message' => 'gedung berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(gedung $gedung)
    {
        $gedung = gedung::findOrFail($gedung->id_gedung);
        return view('admin.lokasi.detail', ['gedung' => $gedung]);
    }


    public function edit(gedung $gedung)
    {
        $gedung = gedung::findOrFail($gedung->id_gedung);

        $gedung = Gedung::all();
        $jurusan = jurusan::all();
        $lantai = Lantai::where('id_gedung', $gedung->id_gedung)->get();
        $ruangan = Ruangan::where('id_lantai', $gedung->id_lantai)->get();

        return view('admin.lokasi.edit', compact('gedung', 'gedung', 'jurusan', 'lantai', 'ruangan'));
    }

    public function update(Request $request, gedung $gedung)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'gedung' => 'required|integer|exists:gedung,id_gedung',
                'lantai' => 'required|integer|exists:lantai,id_lantai',
                'ruangan' => 'required|integer|exists:ruangan,id_ruangan',
                'nama_gedung' => 'required|string|min:2|max:35',
                'jurusan' => 'required|integer|exists:jurusan,id_jurusan',
                'kondisi' => 'required|string|in:LAYAK,RUSAK',
                'deskripsi' => 'required|string|min:10|max:255',
                'urgensi' => 'required|string|in:DARURAT,PENTING,BIASA',
                'foto_gedung' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $gedung = gedung::find($gedung->id_gedung);
            if (!$gedung) {
                return response()->json([
                    'status' => false,
                    'message' => 'gedung tidak ditemukan',
                ]);
            }

            $fileName = $gedung->foto_gedung;
            if ($request->hasFile('foto_gedung')) {
                if ($fileName && Storage::disk('public')->exists('uploads/img/foto_gedung/' . $fileName)) {
                    Storage::disk('public')->delete('uploads/img/foto_gedung/' . $fileName);
                }
                $file = $request->file('foto_gedung');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/img/foto_gedung', $fileName, 'public');
            }

            $gedung->update([
                'id_ruangan' => $request->ruangan,
                'nama_gedung' => $request->nama_gedung,
                'id_jurusan' => $request->jurusan,
                'kondisi' => $request->kondisi,
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'foto_gedung' => $fileName
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data gedung berhasil diperbarui',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data gedung gagal diperbarui',
        ]);
    }

    public function import()
    {
        return view('admin.lokasi.import');
    }

    public function import_file(Request $request)
    {
        //if ($request->ajax() || $request->wantsJson()) {
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
                        'kode_gedung' => $value['A'],
                        'nama_gedung' => $value['B'],
                        'id_jurusan' => $value['C'],
                        'id_ruangan' => $value['D'],
                        'urgensi' => strtoupper($value['E']),
                        'kondisi' => strtoupper($value['F']),
                        'deskripsi' => $value['G'],
                        'foto_gedung' => '0',
                        'id_periode' => $periode->id_periode,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                gedung::insertOrIgnore($insert);
            }

            return redirect()->back()->with('success', 'Data berhasil diimport.');
        } else {
            return redirect()->back()->withErrors(['general' => 'Data gagal diimport.']);
        }
        //}
        // return response()->json([
        //     'status' => false,
        //     'message' => 'Data gagal diimport'
        // ]);
    }

    public function export_pdf()
    {
        // Ini untuk mengambil data gedung yang sudah difilter (masih gagal)
        // $gedung = $this->queried_gedung;

        $gedung = gedung::with(['jurusan', 'ruangan'])->get();

        $headers = ['Kode', 'Nama', 'jurusan', 'Lokasi', 'Kondisi', 'Urgensi'];
        $data = $gedung->map(function ($item) {
            $kondisi = Str::ucfirst(Str::lower($item->kondisi->value));
            $urgensi = Str::ucfirst(Str::lower($item->urgensi->value));
            return [
                'kode' => $item->kode_gedung,
                'nama' => $item->nama_gedung,
                'jurusan' => $item->jurusan->nama_jurusan,
                'lokasi' => $item->getLokasiString(),
                'kondisi' => $kondisi,
                'urgensi' => $urgensi,
            ];
        })->toArray();
        $sheet = Sheet::make(
            [
                'title' => 'Data gedung',
                'text' => 'Berikut adalah daftar gedung yang terdaftar di sistem.',
                'footer' => 'Dibuat oleh Nabeela',
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_gedung' . date('Y-m-d_H-i-s'),
                'is_landscape' => false, // Mengatur orientasi kertas menjadi landscape
            ]
        );
        return $sheet->toPdf();
    }
    public function export_excel()
    {
        // Ini untuk mengambil data gedung yang sudah difilter (masih gagal)
        // $gedung = $this->queried_gedung;

        $gedung = gedung::with(['jurusan', 'ruangan'])->get();

        $headers = ['Kode', 'Nama', 'jurusan', 'Lokasi', 'Kondisi', 'Urgensi'];
        $data = $gedung->map(function ($item) {
            $kondisi = Str::ucfirst(Str::lower($item->kondisi->value));
            $urgensi = Str::ucfirst(Str::lower($item->urgensi->value));
            return [
                'kode' => $item->kode_gedung,
                'nama' => $item->nama_gedung,
                'jurusan' => $item->jurusan->nama_jurusan,
                'lokasi' => $item->getLokasiString(),
                'kondisi' => $kondisi,
                'urgensi' => $urgensi,
            ];
        })->toArray();
        $sheet = Sheet::make(
            [
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_gedung' . date('Y-m-d_H-i-s'),
            ]
        );
        return $sheet->toXls();
    }
}
