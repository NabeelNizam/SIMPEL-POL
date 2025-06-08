<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Biaya;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\UmpanBalik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SarprasController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard Sarana Prasarana',
            'list' => ['Home', 'dashboard']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'home';

        $periode = Periode::all();

        $totalLaporan = Aduan::count();
        $tertunda = Aduan::where('status', 'tertunda')->count();
        $dalamProses = Aduan::where('status', 'dalam_proses')->count();
        $selesai = Aduan::where('status', 'selesai')->count();

        // Data untuk grafik
        $umpanBalik = UmpanBalik::selectRaw('COUNT(*) as total, rating')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        $periodeId = $request->input('id_periode');
        $statusPerbaikan = Aduan::selectRaw('COUNT(*) as total, status')->groupBy('status')->get();
        $kategoriKerusakan = Aduan::with('fasilitas.kategori') // Ambil relasi kategori dari fasilitas
            ->get()
            ->groupBy(fn($item) => $item->fasilitas->kategori->nama_kategori ?? 'Tidak ada kategori') // Kelompokkan berdasarkan kategori
            ->map(function ($items, $kategori) {
                return [
                    'kategori' => $kategori,
                    'total' => $items->count(), // Hitung jumlah aduan dalam kategori
                ];
            })
            ->values();
        $trenKerusakanRaw = Aduan::selectRaw('COUNT(*) as total, MONTH(tanggal_aduan) as bulan')
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        // Pastikan semua bulan (1 hingga 12) ada dalam data
        $trenKerusakan = collect(range(1, 12))->map(function ($bulan) use ($trenKerusakanRaw) {
            $data = $trenKerusakanRaw->firstWhere('bulan', $bulan);
            return [
                'bulan' => $bulan,
                'total' => $data ? $data->total : 0, // Jika tidak ada data, set total ke 0
            ];
        });
        $trenAnggaranRaw = Biaya::selectRaw('SUM(besaran) as total, MONTH(inspeksi.tanggal_mulai) as bulan')
            ->join('inspeksi', 'biaya.id_inspeksi', '=', 'inspeksi.id_inspeksi') // Hubungkan tabel biaya dengan perbaikan
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // Pastikan semua bulan (1 hingga 12) ada dalam data
        $trenAnggaran = collect(range(1, 12))->map(function ($bulan) use ($trenAnggaranRaw) {
            $data = $trenAnggaranRaw->firstWhere('bulan', $bulan);
            return [
                'bulan' => $bulan,
                'total' => $data ? $data->total : 0, // Jika tidak ada data, set total ke 0
            ];
        });

        return view('sarpras.dashboard', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'periode',
            'totalLaporan',
            'tertunda',
            'dalamProses',
            'selesai',
            'umpanBalik',
            'statusPerbaikan',
            'kategoriKerusakan',
            'trenKerusakan',
            'trenAnggaran'
        ));
    }
    public function SOPDownload($filename)
    {
        $role = 'sarpras'; // Bisa juga ambil dari auth jika dinamis
        $filePath = "documents/{$role}/{$filename}";

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        // Cegah akses ke file berbahaya
        if (
            str_contains($filename, '..') ||
            str_contains($filename, '/') ||
            str_contains($filename, '\\')
        ) {
            abort(403, 'Akses tidak sah.');
        }

        // Jalankan download menggunakan response()->download()
        return response()->download(storage_path("app/public/{$filePath}"));
    }
}
