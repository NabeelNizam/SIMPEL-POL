<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Jurusan',
            'list' => ['Home', 'Manajemen Data Jurusan']
        ];

        $page = (object) [
            'title' => 'Daftar jurusan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'jurusan';

        // Query untuk model jurusan
        $query = Jurusan::query();

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->where('nama_jurusan', 'like', "%{$request->search}%");
        }

        // Sorting
        $sortColumn = $request->sort_column ?? 'created_at'; // Default sorting by created_at
        $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 10); // default 10
        $jurusan = $query->paginate($perPage);

        $jurusan->appends(request()->query());

        // Jika permintaan adalah AJAX, kembalikan hanya tabel
        if ($request->ajax()) {
            $html = view('admin.jurusan.jurusan_table', compact('jurusan'))->render();
            return response()->json(['html' => $html]);
        }

        // Kembalikan view utama
        return view('admin.jurusan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'jurusan' => $jurusan,
        ]);
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|min:2|max:5',
            'nama_jurusan' => 'required|string|min:8|max:45',
        ]);
         
        try {
            Jurusan::create([
                'kode_jurusan' => $request->kode_jurusan,
                'nama_jurusan' => $request->nama_jurusan,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan jurusan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan jurusan.']);
        }

        return redirect()->back()->with('success', 'Data jurusan berhasil ditambahkan.');
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit')->with('jurusan', $jurusan);
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|min:2|max:5|unique:jurusan,kode_jurusan,' . $jurusan->id_jurusan . ',id_jurusan',
            'nama_jurusan' => 'required|string|min:8|max:45',
        ]);

        try {
            $jurusan->update([
                'kode_jurusan' => $request->kode_jurusan,
                'nama_jurusan' => $request->nama_jurusan,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengubah jurusan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal mengubah jurusan.']);
        }
        return redirect()->back()->with('success', 'Data jurusan berhasil diubah.');
    }

    public function confirm(Jurusan $jurusan)
    {
        return view('admin.jurusan.confirm')->with('jurusan', $jurusan);
    }

    public function destroy(Jurusan $jurusan)
    {
        try {
            $jurusan->delete();
            return redirect()->back()->with('success', 'Data jurusan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus jurusan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus jurusan.']);
        }
    }

    public function show(Jurusan $jurusan)
    {
        return view('admin.jurusan.detail', ['jurusan' => $jurusan]);
    }

    public function export_excel()
    {
        $jurusan = Jurusan::all();

        $headers = ['Kode Jurusan', 'Nama Jurusan'];
        $data = $jurusan->map(function ($item) {
            return [
                'kode' => $item->kode_jurusan,
                'nama' => $item->nama_jurusan,
            ];
        })->toArray();
        $sheet = Sheet::make(
            [
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_jurusan' . date('Y-m-d_H-i-s'),
            ]
        );
        return $sheet->toXls();
    }

    public function export_pdf()
    {

        $jurusan = Jurusan::all();

        $headers = ['Kode Jurusan', 'Nama Jurusan'];
        $data = $jurusan->map(function ($item) {
            return [
                'kode' => $item->kode_jurusan,
                'nama' => $item->nama_jurusan,
            ];
        })->toArray();
        $sheet = Sheet::make(
        [
                'title' => 'Data Jurusan',
                'text' => 'Berikut adalah daftar jurusan yang terdaftar di sistem.',
                'footer' => 'Dibuat oleh Nabeela',
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_jurusan' . date('Y-m-d_H-i-s'),
                'is_landscape' => false, // Mengatur orientasi kertas menjadi landscape
            ]
        );
        return $sheet->toPdf();
    }
}