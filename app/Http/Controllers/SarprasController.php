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
        $kategoriKerusakan = Perbaikan::selectRaw('COUNT(*) as total, tingkat_kerusakan')->groupBy('tingkat_kerusakan')->get();
        $trenKerusakan = Aduan::selectRaw('COUNT(*) as total, MONTH(created_at) as bulan')->groupBy('bulan')->get();
        $trenAnggaran = Biaya::selectRaw('SUM(besaran) as total, MONTH(created_at) as bulan')->groupBy('bulan')->get();

    return view('sarpras.dashboard', compact(
        'breadcrumb', 'page', 'activeMenu',
        'totalLaporan', 'tertunda', 'dalamProses', 'selesai',
        'umpanBalik', 'statusPerbaikan', 'kategoriKerusakan', 'trenKerusakan', 'trenAnggaran', 'periode'
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
