<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Kategori;
use App\Models\Periode;
use App\Models\UmpanBalik;
use Illuminate\Http\Request;

class AduanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Aduan',
            'list' => ['Home', 'Manajemen Aduan']
        ];

        $page = (object) [
            'title' => 'Daftar aduan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'aduan';

        // Query untuk aduan
        // Ambil aduan, group by fasilitas & periode
        $query = Aduan::with([
            'fasilitas.kategori',
            'fasilitas.ruangan',
            'fasilitas.inspeksi.perbaikan',
            'pelapor',
            'umpan_balik'
        ]);

        // filter periode
        if ($request->id_periode) {
            $query->where('id_periode', $request->id_periode);
        }

        // filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->whereHas('fasilitas', function ($q) use ($request) {
                $q->where('nama_fasilitas', 'like', "%{$request->search}%");
            });
        }

        // Group by fasilitas & periode
        $query->select('id_fasilitas', 'id_periode')
            ->groupBy('id_fasilitas', 'id_periode')
            ->orderBy('id_periode', 'desc')
            ->orderByRaw("FIELD(MAX(status), 'MENUNGGU_DIPROSES', 'SEDANG_INSPEKSI', 'SEDANG_DIPERBAIKI', 'SELESAI') DESC")
            ->orderBy('id_fasilitas', 'asc');

        // Pagination
        $perPage = $request->input('per_page', 10);
        $aduan = $query->paginate($perPage);

        $aduan->appends(request()->query());

        // ambil data periode untuk filter
        $periode = Periode::all();

        if ($request->ajax()) {
            $html = view('admin.aduan.aduan_table', compact('aduan'))->render();
            return response()->json(['html' => $html]);
        }
        return view('admin.aduan.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'periode'));
    }

    public function show_ajax($id_fasilitas)
    {
        // Ambil data aduan berdasarkan id_fasilitas
        $aduan = Aduan::with(['fasilitas.inspeksi.perbaikan', 'fasilitas.inspeksi.biaya', 'fasilitas.ruangan.lantai.gedung', ])->where('id_fasilitas', $id_fasilitas)->firstOrFail();

        // Ambil data perbaikan terkait aduan
        $perbaikan = $aduan->fasilitas->inspeksi->first()->perbaikan;
        $biaya = $aduan->fasilitas->inspeksi->first()->biaya;

        $fasilitas = Fasilitas::with('kategori')->findOrFail($id_fasilitas); // Ambil fasilitas beserta kategori
        $kategori = $fasilitas->kategori;

        

        // Ambil rata-rata rating untuk fasilitas & tanggal aduan yang sama
        $avgRating = null;
        if ($aduan) {
            $avgRating = UmpanBalik::whereHas('aduan', function ($q) use ($aduan) {
                $q->where('id_fasilitas', $aduan->id_fasilitas)
                    ->where('tanggal_aduan', $aduan->tanggal_aduan);
            })->avg('rating');
            $avgRating = $avgRating ? number_format($avgRating, 1) : null;
        }

        return view('admin.aduan.detail', compact('aduan', 'biaya', 'perbaikan', 'fasilitas', 'kategori', 'avgRating'))->render();

    }

    public function comment_ajax($id, Request $request)
    {
        // Ambil fasilitas
        $fasilitas = Fasilitas::with(['aduan.pelapor', 'aduan.umpan_balik'])
            ->findOrFail($id);

        // Filter aduan berdasarkan periode (jika ada)
        $aduan = $fasilitas->aduan()
            ->when($request->id_periode, function ($q) use ($request) {
                $q->where('id_periode', $request->id_periode);
            })
            ->with(['pelapor', 'umpan_balik'])
            ->get();

        return view('admin.aduan.comment', [
            'fasilitas' => $fasilitas,
            'aduan' => $aduan,
        ])->render();
    }

    private function set_sheet()
    {
        $aduan = Aduan::query()
            ->with(['fasilitas', 'fasilitas.inspeksi.perbaikan'])
            ->get();

        $filename = 'riwayat_perbaikan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $sheet = new Sheet(); // Pass the data and filename to the Sheet
        $sheet->title = 'Riwayat Perbaikan';
        $sheet->text = 'Berikut adalah daftar perbaikan fasilitas.';
        $sheet->footer = 'Dibuat oleh Sistem';
        $sheet->header = ['Nama Fasilitas', 'Lokasi', 'Kategori', 'Tanggal Aduan', 'Tanggal Perbaikan'];

        $sheet->data = $aduan->map(function ($item) {
            return [
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

    public function export_pdf(){
        return $this->set_sheet()->toPdf();
    }


}
