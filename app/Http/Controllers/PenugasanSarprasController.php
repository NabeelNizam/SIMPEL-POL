<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Kategori;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenugasanSarprasController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Penugasan',
            'list' => ['Home', 'Laporan Penugasan']
        ];

        $page = (object) [
            'title' => 'Daftar penugasan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penugasan';

        // Query untuk Penugasan
        $query = Inspeksi::with([
                    'fasilitas',
                    'fasilitas.kategori',
                    'periode',
                ])
                ->whereNotNull('tingkat_kerusakan')
                ->whereNotNull('tanggal_selesai');


        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->whereHas('fasilitas', function ($q) use ($request) {
                $q->where('nama_fasilitas', 'like', "%{$request->search}%");
            });
        }

        
        // Filter berdasarkan kategori
        if ($request->id_kategori) {
            $query->whereHas('periode', function ($q) use ($request) {
                $q->where('id_periode', $request->id_periode);
            });
        }

        $query->orderBy('tanggal_selesai', 'desc');

        // dd($query->toSql(), $query->getBindings());


        // Pagination
        $perPage = $request->input('per_page', 10);
        $penugasan = $query->paginate($perPage);

        $penugasan->appends(request()->query());

        // ambil data kategori untuk filter
        $periode = Periode::all();

        if ($request->ajax()) {
            $html = view('sarpras.penugasan.penugasan_table', compact('penugasan'))->render();
            return response()->json(['html' => $html]);
        }

        return view('sarpras.penugasan.index', compact('breadcrumb', 'page', 'activeMenu', 'penugasan', 'periode'));
    }

    // Detail Fasilitas & Laporan Pengaduan nya
    public function show_penugasan(Inspeksi $inspeksi)
    {
        $biaya = Biaya::where('id_inspeksi', $inspeksi->id_inspeksi)->get();
        return view('sarpras.penugasan.detail', ['inspeksi' => $inspeksi, 'biaya' => $biaya]);
    }

    // Method menugaskan teknisi untuk inspeksi
    public function penugasan_teknisi($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor'])->withCount('aduan')->findOrFail($id);
        $aduan = $fasilitas->aduan->first();
        $teknisi = User::where('id_role', 3)->get();
        return view('sarpras.pengaduan.edit', ['fasilitas' => $fasilitas, 'aduan' => $aduan, 'teknisi' => $teknisi]);
    }
    
    public function confirm_penugasan(Request $request, $id)
    {
        $periodeSekarang = Periode::getPeriodeAktif();
        
        try {
            Inspeksi::create([
            'id_user_teknisi' => $request->id_teknisi,
            'id_user_sarpras' => auth()->user()->id_user,
            'id_fasilitas' => $id,
            'id_periode' => $periodeSekarang->id_periode,
            'tanggal_mulai' => now(),
        ]);    
        // dd($query->toSql(), $query->getBindings());
            return redirect()->back()->with('success', 'Berhasil menugaskan inspeksi.');
        } catch (\Exception $e) {
            Log::error('Gagal menugaskan teknisi: '.$e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menugaskan teknisi.']);
        }
    }
}
