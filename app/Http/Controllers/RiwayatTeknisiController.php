<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Http\Enums\Kondisi;
use App\Http\Sheet\Sheet;
use App\Models\Aduan;
use App\Models\Prioritas;
use Illuminate\Http\Request;

class RiwayatTeknisiController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Perbaikan',
            'list' => ['Home', 'Riwayat Perbaikan']
        ];

        $page = (object) [
            'title' => 'Daftar aduan dengan status selesai'
        ];

        $activeMenu = 'riwayat';

        // Query untuk aduan dengan status selesai
        $query = Aduan::with(['fasilitas', 'prioritas', 'perbaikan'])->where('status', 'Selesai');

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

        // Filter berdasarkan prioritas
        if ($request->id_prioritas) {
            $query->where('id_prioritas', $request->id_prioritas);
        }

        // Sorting
        $sortColumn = $request->sort_column ?? 'tanggal_aduan';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 10);
        $aduan = $query->paginate($perPage);

        $aduan->appends(request()->query());

        // Ambil data kategori dan prioritas untuk filter
        $kategori = Kategori::all();
        $prioritas = Prioritas::all();

        if ($request->ajax()) {
            $html = view('teknisi.riwayat.riwayat_table', compact('aduan'))->render();
            return response()->json(['html' => $html]);
        }

        return view('teknisi.riwayat.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori', 'prioritas'));
    }
    private function set_sheet()
    {
        //  $aduan = Aduan::query()->where('status', 'selesai')

         $aduan = Aduan::query()->where('status', 'menunggu_diproses')
            ->with(['fasilitas', 'perbaikan'])
            ->get();

        $filename = 'riwayat_perbaikan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $sheet = new Sheet(); // Pass the data and filename to the Sheet
        $sheet->title = 'Riwayat Perbaikan';
        $sheet->text = 'Berikut adalah daftar riwayat perbaikan yang telah selesai.';
        $sheet->footer = 'Dibuat oleh Sistem';
        $sheet->header = ['Nama Fasilitas', 'Kategori', 'Tanggal Aduan', 'Tanggal Perbaikan'];

        $sheet->data = $aduan->map(function ($item) {
            return [
                'nama_fasilitas' => $item->fasilitas->nama_fasilitas,
                'kategori' => $item->fasilitas->kategori->nama_kategori,
                'tanggal_aduan' => $item->tanggal_aduan,
                'tanggal_perbaikan' => $item->perbaikan ? $item->perbaikan->tanggal_selesai : 'Belum diperbaiki',
            ];
        })->toArray();
        $sheet->filename = $filename;

        return $sheet;
    }
    public function export_excel()
    {

        return $this->set_sheet()->toXls();
    }
    public function export_pdf()
    {
        return $this->set_sheet()->toPdf();
    }
}
