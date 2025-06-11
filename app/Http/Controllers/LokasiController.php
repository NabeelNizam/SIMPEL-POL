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
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class LokasiController extends Controller
{
    //
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

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
        return view('admin.lokasi.create');
    }
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama_gedung' => ['required', 'string', 'max:30'],
            'lantai.*.nama_lantai' => ['required', 'string', 'max:30'],
            'lantai.*.ruangan.*' => ['required', 'string', 'max:30'],
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        try {
            $gedung = Gedung::create([
                'nama_gedung' => $request->nama_gedung,
                'kode_gedung' => substr(md5($request->nama_gedung), 0, 2) . $this->faker->unique()->randomNumber(3),
            ]);

            foreach ($request->lantai as $item) {
                $lantai = Lantai::create([
                    'id_gedung' => $gedung->id_gedung,
                    'nama_lantai' => $item['nama_lantai'],
                ]);
                foreach ($item['ruangan'] as $jtem) {
                    Ruangan::create([
                        'id_lantai' => $lantai->id_lantai,
                        'nama_ruangan' => $jtem,
                        'kode_ruangan' => substr(md5($jtem), 0, 2) . $this->faker->unique()->randomNumber(3),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Data gedung berhasil disimpan.');
        } catch (\Exception $e) {
            // throw $e;
            // Log::error('Gagal simpan gedung: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menyimpan data.']);
        }
    }

    public function confirm(Gedung $gedung)
    {
        return view('admin.lokasi.confirm')->with([
            'gedung' => $gedung
        ]);
    }

    public function destroy(Request $request, Gedung $gedung)
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


    public function show(Gedung $gedung)
    {
        // Ambil data gedung beserta lantai dan ruangan
        $gedung = Gedung::with(['lantai.ruangan'])->findOrFail($gedung->id_gedung);

        // Kirim data ke view
        return view('admin.lokasi.detail', compact('gedung'));
    }

    public function edit(Gedung $gedung)
    {
        // Ambil data gedung beserta lantai dan ruangan
        $gedung = Gedung::with(['lantai.ruangan'])->findOrFail($gedung->id_gedung);

        // Kirim data ke view
        return view('admin.lokasi.edit', compact('gedung'));
    }
    public function getLastRuanganId()
    {
        // Ambil ID ruangan terakhir dari database
        $lastId = Ruangan::max('id_ruangan') ?? 0; // Jika tidak ada ruangan, mulai dari 0
        return response()->json(['lastId' => $lastId]);
    }

    public function update(Request $request, Gedung $gedung)
    {
        // dd($request->all());
        $validation = Validator::make($request->all(), [
            'nama_gedung' => ['required', 'string', 'max:30'],
            'lantai.*.nama_lantai' => ['required', 'string', 'max:30'],
            'lantai.*.ruangan.*.nama_ruangan' => ['required', 'string', 'max:30'],
        ]);
        // dd($validation->errors());
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        // update nama gedung
        $gedung->update([
            'nama_gedung' => $request->nama_gedung,
        ]);

        // mapping lantai dan ruangan
        $lantais = new Collection();
        $ruangans = new Collection();
        if (isset($request->lantai) && is_array($request->lantai)) {
            foreach ($request->lantai as $id_lantai => $item) {
                // Ambil model lantai
                $lantai = Lantai::findOrNew($id_lantai);

                if (isset($item['nama_lantai']) && $lantai->nama_lantai != $item['nama_lantai']) {
                    $lantai->nama_lantai = $item['nama_lantai'];
                }
                // set id_gedung
                $lantai->id_gedung = $gedung->id_gedung;
                $lantais->add($lantai);

                if (isset($item['ruangan']) && is_array($item['ruangan'])) {
                    foreach ($item['ruangan'] as $id_ruangan => $data_ruangan) {
                        // Ambil model ruangan
                        $ruangan = Ruangan::findOrNew($id_ruangan);
                        // Set nama ruangan baru
                        if (isset($data_ruangan['nama_ruangan']) && $ruangan->nama_ruangan != $data_ruangan['nama_ruangan']) {
                            $ruangan->nama_ruangan = $data_ruangan['nama_ruangan'];
                            $ruangan->generateKode();
                        }
                        // set id lantai
                        if (!isset($ruangan->id_lantai)) {
                            $ruangan->id_lantai = $lantai->id_lantai;
                        }
                        $ruangans->add($ruangan);
                    }
                }
            }
        }
        $current_lantais = Lantai::query()->where('id_gedung', $gedung->id_gedung)->get();

        //deleting lantai
        $lantais_to_delete = $this->getEloquentCollectionDifference($current_lantais, $lantais, 'id_lantai');
        // dd($lantais, $current_lantais, $lantais_to_delete);
        foreach ($lantais_to_delete as $item) {
            try {
                $item->ruangan()->delete();
                $item->delete();
            } catch (\Exception $e) {
                return redirect()->route('admin.lokasi')->withErrors(['general' => 'Tindakan terlarang: Ruangan memiliki fasilitas.']);
            }
        }

        // adding lantai
        $lantais_to_add = $this->getEloquentCollectionDifference($lantais, $current_lantais, 'id_lantai');
        // dd($lantais_to_add);
        foreach ($lantais_to_add as $item) {
            try {
                $item->save();
            } catch (\Exception $e) {
                throw $e;
            }
        }

        $current_ruangans = new Collection();
        foreach ($current_lantais as $current_lantai) {
            foreach ($current_lantai->ruangan as $current_ruangan) {
                $current_ruangans->add($current_ruangan);
            }
        }

        // dd($current_ruangans);

        //deleting ruangan
        $ruangans_to_delete = $this->getEloquentCollectionDifference($current_ruangans, $ruangans, 'id_ruangan');
        foreach ($ruangans_to_delete as $item) {
            try {
                $item->delete();
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['Tindakan terlarang: ' => 'Ruangan memiliki fasilitas.']);
            }
        }

        // adding ruangan
        $ruangans_to_add = $this->getEloquentCollectionDifference($ruangans, $current_ruangans, 'id_ruangan');
        foreach ($ruangans_to_add as $item) {
            $item->save();
        }

        return redirect()->back()->with('success', 'Data gedung berhasil diupdate.');
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
    private function getEloquentCollectionDifference(Collection $collection1, Collection $collection2, string $key = 'id', bool $isReversed = false): Collection
    {
        if ($isReversed) {
            $collection1Keys = $collection1->pluck($key);
            return $collection2->whereNotIn($key, $collection1Keys);
        }
        $collection2Keys = $collection2->pluck($key);
        return $collection1->whereNotIn($key, $collection2Keys);
    }
}
