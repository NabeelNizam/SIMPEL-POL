<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Kategori;
use App\Models\Kriteria;
use App\Models\Pegawai;
use App\Models\Perbaikan;
use App\Models\UmpanBalik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $query = Aduan::with(['pelapor', 'fasilitas.kategori', 'fasilitas.ruangan', 'umpan_balik', 'perbaikan']);

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

        // Sorting
        $sortColumn = $request->sort_column ?? 'tanggal_aduan';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 10);
        $aduan = $query->paginate($perPage);

        $aduan->appends(request()->query());

        // ambil data kategori untuk filter
        $kategori = Kategori::all();

        if ($request->ajax()) {
            $html = view('admin.aduan.aduan_table', compact('aduan'))->render();
            return response()->json(['html' => $html]);
        }
        return view('admin.aduan.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'kategori'));
    }

    public function show_ajax($id)
    {
        $aduan = Aduan::with([
            'pelapor',
            'fasilitas.kategori',
            'fasilitas.ruangan.lantai.gedung',
            'umpan_balik',
            'perbaikan.teknisi.pegawai',
            'perbaikan.biaya'
        ])->findOrFail($id);
        $user = $aduan->pelapor;

        // Ambil rata-rata rating untuk fasilitas & tanggal aduan yang sama
        $avgRating = UmpanBalik::whereHas('aduan', function ($q) use ($aduan) {
            $q->where('id_fasilitas', $aduan->id_fasilitas)
                ->where('tanggal_aduan', $aduan->tanggal_aduan);
        })
            ->avg('rating');

        $avgRating = $avgRating ? number_format($avgRating, 1) : null;

        return view('admin.aduan.detail', compact('aduan', 'user', 'avgRating'))->render();
    }

    public function comment_ajax($id)
    {
        $aduan = Aduan::with(['umpan_balik', 'pelapor'])->findOrFail($id);
        $aduanFasilitas = Aduan::with('pelapor')
            ->where('id_fasilitas', $aduan->id_fasilitas)
            ->where('tanggal_aduan', $aduan->tanggal_aduan)
            ->get();

        $pelaporFasilitas = $aduanFasilitas->pluck('pelapor')->unique('id_user')->filter();

        return view('admin.aduan.comment', [
            'aduan' => $aduan,
            'pelaporFasilitas' => $pelaporFasilitas
        ])->render();
    }

    // Fitur Pengaduan yang akan di inspeksi
    // -User : Sarpras
    public function pengaduan(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Home', 'Laporan Pengaduan']
        ];

        $page = (object) [
            'title' => 'Daftar Pengaduan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengaduan';

        // Query untuk Pengaduan
        $query = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor.role']);

if ($request->has('filter_user') && $request->filter_user != 'all') {
    $filterRole = $request->filter_user;

    // Filter dan count hanya aduan dari pelapor dengan role tertentu
    $query->withCount([
        'aduan as aduan_count' => function ($q) use ($filterRole) {
            $q->whereHas('pelapor', function ($q2) use ($filterRole) {
                $q2->where('id_role', $filterRole);
            });
        }
    ]);

    // Filter hanya fasilitas yang punya aduan dari pelapor dengan role tersebut
    $query->whereHas('aduan', function ($q) use ($filterRole) {
        $q->whereHas('pelapor', function ($q2) use ($filterRole) {
            $q2->where('id_role', $filterRole);
        });
    });

} else {
    // Jika tidak difilter berdasarkan role
    $query->withCount('aduan');
}



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

        $query->orderBy('aduan_count', 'desc');

        // dd($query->toSql(), $query->getBindings());


        // Pagination
        $perPage = $request->input('per_page', 10);
        $pengaduan = $query->paginate($perPage);

        $pengaduan->appends(request()->query());

        // ambil data kategori untuk filter
        $kategori = Kategori::all();

        if ($request->ajax()) {
            $html = view('sarpras.pengaduan.table', compact('pengaduan'))->render();
            return response()->json(['html' => $html]);
        }

        return view('sarpras.pengaduan.index', compact('breadcrumb', 'page', 'activeMenu', 'pengaduan', 'kategori'));
    }

    // Detail Fasilitas & Laporan Pengaduan nya
    public function show_pengaduan($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor'])->withCount('aduan')->findOrFail($id);
        $aduan = $fasilitas->aduan->first();
        // dd($aduan);
        return view('sarpras.pengaduan.detail', ['fasilitas' => $fasilitas, 'aduan' => $aduan]);
    }

    // Method menugaskan teknisi untuk inspeksi
    public function penugasan_teknisi($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor'])->withCount('aduan')->findOrFail($id);
        $aduan = $fasilitas->aduan->first();
        $teknisi = User::where('id_role', 3)->get();
        // dd($aduan);
        return view('sarpras.pengaduan.edit', ['fasilitas' => $fasilitas, 'aduan' => $aduan, 'teknisi' => $teknisi]);
    }
    
    public function confirm_penugasan(Request $request, $id)
    {
     
        
        // Inspeksi::create([
        //     'id_teknisi' => $request->id_teknisi,
        //     'id_sarpras' => auth()->user()->id_user,
        //     'id_fasilitas' => $id,
        // ])
        // return view('sarpras.pengaduan.edit', ['fasilitas' => $fasilitas, 'aduan' => $aduan, 'teknisi' => $teknisi]);
    }


}
