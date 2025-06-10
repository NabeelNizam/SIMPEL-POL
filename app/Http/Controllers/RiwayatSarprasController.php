<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Periode;
use App\Models\UmpanBalik;
use Illuminate\Http\Request;

class RiwayatSarprasController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Riwayat Perbaikan',
            'list' => ['Home', 'Riwayat Perbaikan']
        ];

        $page = (object) [
            'title' => 'Daftar aduan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'riwayat';

        // Query untuk aduan dengan status selesai
        $query = Aduan::with(['fasilitas', 'fasilitas.inspeksi.perbaikan', 'fasilitas.ruangan'])->where('status', 'Selesai');

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->whereHas('fasilitas', function ($q) use ($request) {
                $q->where('nama_fasilitas', 'like', "%{$request->search}%");
            });
        }

        // filter periode
        if ($request->id_periode) {
            $query->where('id_periode', $request->id_periode);
        }

        // Sorting
        $sortColumn = $request->sort_column ?? 'tanggal_aduan';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 10);
        $aduan = $query->paginate($perPage);
        $aduan->appends(request()->query());

        // ambil data periode untuk filter
        $periode = Periode::all();

        if ($request->ajax()) {
            $html = view('sarpras.riwayat.riwayat_table', compact('aduan'))->render();
            return response()->json(['html' => $html]);
        }


        return view('sarpras.riwayat.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'periode'));
    }

    public function show_ajax($id_fasilitas)
    {
        // Ambil data aduan berdasarkan id_fasilitas
        $aduan = Aduan::with(['fasilitas.inspeksi.perbaikan', 'fasilitas.inspeksi.biaya', 'fasilitas.ruangan.lantai.gedung',])
            ->where('id_fasilitas', $id_fasilitas)
            ->firstOrFail();

        // Ambil data perbaikan terkait aduan
        $perbaikan = $aduan->fasilitas->inspeksi->first()->perbaikan;
        $biaya = $aduan->fasilitas->inspeksi->first()->biaya;

        $fasilitas = Fasilitas::with('kategori')->findOrFail($id_fasilitas); // Ambil fasilitas beserta kategori
        $kategori = $fasilitas->kategori;

        $jumlahAduan = Aduan::where('id_fasilitas', $id_fasilitas)->count();

        // Ambil rata-rata rating untuk fasilitas & tanggal aduan yang sama
        $avgRating = null;
        if ($aduan) {
            $avgRating = UmpanBalik::whereHas('aduan', function ($q) use ($aduan) {
                $q->where('id_fasilitas', $aduan->id_fasilitas)
                    ->where('tanggal_aduan', $aduan->tanggal_aduan);
            })->avg('rating');
            $avgRating = $avgRating ? number_format($avgRating, 1) : null;
        }

        return view('sarpras.riwayat.detail', compact('aduan', 'biaya', 'perbaikan', 'fasilitas', 'avgRating', 'jumlahAduan'))->render();
    }

    public function comment_ajax($id_fasilitas)
    {
        // Ambil semua aduan berdasarkan id_fasilitas
        $aduan = Aduan::with(['pelapor', 'fasilitas', 'umpan_balik'])
            ->where('id_fasilitas', $id_fasilitas)
            ->get();

        // Ambil semua umpan balik terkait aduan di fasilitas ini
        $umpanBalik = UmpanBalik::whereIn('id_aduan', $aduan->pluck('id_aduan'))->get();

        return view('sarpras.riwayat.comment', [
            'aduan' => $aduan,
            'umpan_balik' => $umpanBalik,
        ])->render();
    }

    private function set_sheet()
    {
        $aduan = Aduan::query()->where('status', 'selesai')
            ->with(['fasilitas', 'fasilitas.inspeksi.perbaikan'])
            ->get();

        $filename = 'riwayat_perbaikan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $sheet = new Sheet(); // Pass the data and filename to the Sheet
        $sheet->title = 'Riwayat Perbaikan';
        $sheet->text = 'Berikut adalah daftar perbaikan fasilitas.';
        $sheet->footer = 'Dibuat oleh Sistem';
        $sheet->header = ['Periode', 'Nama Fasilitas', 'Lokasi', 'Kategori', 'Tanggal Aduan', 'Tanggal Perbaikan'];

        $sheet->data = $aduan->map(function ($item) {
            return [
                'periode' => $item->periode->kode_periode,
                'nama_fasilitas' => $item->fasilitas->nama_fasilitas,
                'lokasi' => $item->fasilitas->ruangan->lantai->gedung->nama_gedung . ' - ' . $item->fasilitas->ruangan->lantai->nama_lantai . ' - ' . $item->fasilitas->ruangan->nama_ruangan,
                'kategori' => $item->fasilitas->kategori->nama_kategori,
                'tanggal_aduan' => $item->tanggal_aduan,
                'tanggal_perbaikan' => $item->fasilitas->inspeksi->first()->perbaikan ? $item->fasilitas->inspeksi->first()->perbaikan->tanggal_selesai : 'Belum diperbaiki',
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
