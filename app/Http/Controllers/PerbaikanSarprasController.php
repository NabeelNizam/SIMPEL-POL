<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Perbaikan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PerbaikanSarprasController extends Controller
{
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Perbaikan Sarpras',
        'list' => ['Home', 'Perbaikan Sarpras']
    ];

    $page = (object) [
        'title' => 'Perbaikan Sarpras'
    ];

    $activeMenu = 'perbaikan';

    // Query untuk mengambil data melalui tabel Aduan
    $query = Aduan::with(['fasilitas.kategori', 'perbaikan.teknisi'])
        ->whereIn('status', ['sedang_diperbaiki', 'selesai']) // Filter status
        ->when($request->id_periode, function ($q) use ($request) {
            $q->where('id_periode', $request->id_periode); // Filter berdasarkan periode
        })
        ->whereHas('perbaikan', function ($q) {
            $q->whereColumn('aduan.id_fasilitas', 'perbaikan.id_fasilitas') // Cocokkan id_fasilitas
                ->whereColumn('aduan.id_periode', 'perbaikan.id_periode'); // Cocokkan id_periode
        });

    $perPage = $request->input('per_page', 10);
    $aduan = $query->paginate($perPage);

    $periode = Periode::all();
    $status = \App\Http\Enums\Status::cases();

    if ($request->ajax()) {
        $html = view('sarpras.perbaikan.perbaikan_table', compact('aduan'))->render();
        return response()->json(['html' => $html]);
    }

    return view('sarpras.perbaikan.index', compact('breadcrumb', 'page', 'activeMenu', 'aduan', 'periode', 'status'));
}
}