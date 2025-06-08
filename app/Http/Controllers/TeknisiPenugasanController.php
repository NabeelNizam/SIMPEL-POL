<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Perbaikan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeknisiPenugasanController extends Controller
{
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Dashboard Teknisi',
        'list' => ['Home', 'dashboard']
    ];

    $page = (object) [
        'title' => 'Daftar penugasan yang terdaftar dalam sistem'
    ];

    $activeMenu = 'penugasan';

    $periode = Periode::all();

    // Query untuk model Inspeksi
   $query = Inspeksi::with([
        'teknisi',
        'fasilitas.ruangan.lantai.gedung',
        'fasilitas.kategori',
        'periode'
    ])
    ->whereHas('fasilitas.aduan', function ($q) {
        $q->where('status', \App\Http\Enums\Status::SEDANG_INSPEKSI); // Filter status SEDANG_INSPEKSI
    });


// Filter berdasarkan pencarian
if ($request->search) {
    $query->whereHas('fasilitas', function ($q) use ($request) {
        $q->where('nama_fasilitas', 'like', "%{$request->search}%");
    });
}

// Filter berdasarkan periode
if ($request->id_periode) {
    $query->where('id_periode', $request->id_periode);
}

// Sorting
$sortColumn = $request->sort_column ?? 'created_at';
$sortDirection = $request->sort_direction ?? 'asc';
$query->orderBy($sortColumn, $sortDirection);

// Pagination
$perPage = $request->input('per_page', 10);
$penugasan = $query->paginate($perPage);

    $penugasan->appends(request()->query());

    // Jika permintaan adalah AJAX, kembalikan hanya tabel
    if ($request->ajax()) {
        $html = view('teknisi.penugasan.penugasan_table', compact('penugasan'))->render();
        return response()->json(['html' => $html]);
    }

    // Kembalikan view utama
    return view('teknisi.penugasan.index', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'activeMenu' => $activeMenu,
        'penugasan' => $penugasan,
        'periode' => $periode,
    ]);
}

public function show_ajax($id_inspeksi)
{
    // Ambil data inspeksi beserta relasi terkait
    $inspeksi = Inspeksi::with([
        'fasilitas.ruangan.lantai.gedung',
        'fasilitas.kategori',
        'biaya' // Relasi langsung ke biaya
    ])->findOrFail($id_inspeksi);

    // Ambil data fasilitas
    $fasilitas = $inspeksi->fasilitas;

    // Ambil data biaya langsung dari inspeksi
    $biaya = $inspeksi->biaya;

    // Ambil status aduan berdasarkan fasilitas dan periode yang sama
    $aduan = $fasilitas->aduan()
        ->where('id_periode', $inspeksi->id_periode)
        ->first();

    $statusAduan = $aduan->status->value ?? '-';

    // Data untuk view
    return view('teknisi.penugasan.detail', compact('inspeksi', 'fasilitas', 'biaya', 'statusAduan'))->render();
}
public function edit_ajax($id_inspeksi)
{
    // Ambil data inspeksi beserta relasi terkait
    $inspeksi = Inspeksi::with([
        'fasilitas.ruangan.lantai.gedung',
        'fasilitas.kategori',
        'biaya', // Relasi ke biaya
        'perbaikan' // Relasi ke perbaikan
    ])->findOrFail($id_inspeksi);

    // Ambil data fasilitas
    $fasilitas = $inspeksi->fasilitas;

    // Ambil data biaya
    $biaya = $inspeksi->biaya;

    // Ambil status aduan berdasarkan fasilitas dan periode yang sama
    $aduan = $fasilitas->aduan()
        ->where('id_periode', $inspeksi->id_periode)
        ->first();

    $statusAduan = $aduan->status->value ?? '-';

    // Return view edit
    return view('teknisi.penugasan.edit', compact('inspeksi', 'fasilitas', 'biaya', 'statusAduan'));
}

public function update_ajax(Request $request, $id_inspeksi)
{
    // Validasi input
    $request->validate([
        'tingkat_kerusakan' => 'required|in:PARAH,SEDANG,RINGAN',
        'deskripsi_pekerjaan' => 'nullable|string',
        'biaya.*.keterangan' => 'required|string',
        'biaya.*.besaran' => 'required|numeric|min:0',
    ]);

    // Ambil data inspeksi
    $inspeksi = Inspeksi::findOrFail($id_inspeksi);

    // Update tingkat kerusakan
    $inspeksi->tingkat_kerusakan = $request->tingkat_kerusakan;
    $inspeksi->save();

    // Update deskripsi pekerjaan
    if ($inspeksi->perbaikan) {
        $inspeksi->perbaikan->deskripsi = $request->deskripsi_pekerjaan;
        $inspeksi->perbaikan->save();
    }

    // Update rincian anggaran
    $inspeksi->biaya()->delete(); // Hapus semua biaya lama
    foreach ($request->biaya as $biaya) {
        $inspeksi->biaya()->create([
            'keterangan' => $biaya['keterangan'],
            'besaran' => $biaya['besaran'],
        ]);
    }

    // Redirect atau response JSON
    return response()->json(['message' => 'Data berhasil diperbarui.']);
}
}
