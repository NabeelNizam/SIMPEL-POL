<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;


class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Kategori',
            'list' => ['Home', 'Manajemen Data Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori';

        // Query untuk model kategori
        $query = Kategori::query();

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->where('nama_kategori', 'like', "%{$request->search}%");
        }

        // Sorting
        $sortColumn = $request->sort_column ?? 'created_at'; // Default sorting by created_at
        $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 10); // default 10
        $kategori = $query->paginate($perPage);

        $kategori->appends(request()->query());

        // Jika permintaan adalah AJAX, kembalikan hanya tabel
        if ($request->ajax()) {
            $html = view('admin.kategori.kategori_table', compact('kategori'))->render();
            return response()->json(['html' => $html]);
        }

        // Kembalikan view utama
        return view('admin.kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|string|min:2|max:5',
            'nama_kategori' => 'required|string|min:5|max:30',
        ]);
         
        try {
            Kategori::create([
                'kode_kategori' => $request->kode_kategori,
                'nama_kategori' => $request->nama_kategori,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan kategori: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan kategori.']);
        }

        return redirect()->back()->with('success', 'Data kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit')->with('kategori', $kategori);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kode_kategori' => 'required|string|min:2|max:5|unique:kategori,kode_kategori,' . $kategori->id_kategori . ',id_kategori',
            'nama_kategori' => 'required|string|min:5|max:30',
        ]);

        try {
            $kategori->update([
                'kode_kategori' => $request->kode_kategori,
                'nama_kategori' => $request->nama_kategori,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengubah kategori: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal mengubah kategori.']);
        }
        return redirect()->back()->with('success', 'Data kategori berhasil diubah.');
    }

    public function confirm(Kategori $kategori)
    {
        return view('admin.kategori.confirm')->with('kategori', $kategori);
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();
            return redirect()->back()->with('success', 'Data kategori berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus kategori: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus kategori.']);
        }
    }

    public function show(Kategori $kategori)
    {
        return view('admin.kategori.detail', ['kategori' => $kategori]);
    }

    public function export_excel()
    {
        $kategori = Kategori::all();

        $headers = ['Kode Kategori', 'Nama Kategori'];
        $data = $kategori->map(function ($item) {
            return [
                'kode' => $item->kode_kategori,
                'nama' => $item->nama_kategori,
            ];
        })->toArray();
        $sheet = Sheet::make(
            [
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_kategori' . date('Y-m-d_H-i-s'),
            ]
        );
        return $sheet->toXls();
    }

    public function export_pdf()
    {

        $kategori = Kategori::all();

        $headers = ['Kode Kategori', 'Nama Kategori'];
        $data = $kategori->map(function ($item) {
            return [
                'kode' => $item->kode_kategori,
                'nama' => $item->nama_kategori,
            ];
        })->toArray();
        $sheet = Sheet::make(
        [
                'title' => 'Data Kategori',
                'text' => 'Berikut adalah daftar kategori yang terdaftar di sistem.',
                'footer' => 'Dibuat oleh Nabeela',
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_kategori' . date('Y-m-d_H-i-s'),
                'is_landscape' => false, // Mengatur orientasi kertas menjadi landscape
            ]
        );
        return $sheet->toPdf();
    }

    public function import()
    {
        return view('admin.kategori.import');
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
                        'kode_kategori' => $value['A'],
                        'nama_kategori' => $value['B'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                Kategori::insertOrIgnore($insert);
            }
            return redirect()->back()->with('success', 'Data berhasil diimport.');
        } else {
            return redirect()->back()->withErrors(['general' => 'Data gagal diimport.']);
        }
    }
}
