<?php

namespace App\Http\Controllers;

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

    $query = Perbaikan::with(['aduan.fasilitas.kategori', 'teknisi', 'aduan'])
        ->when($request->id_periode, function ($q) use ($request) {
            $q->whereHas('aduan', function ($query) use ($request) {
                $query->where('id_periode', $request->id_periode);
            });
        })
        ->when($request->status, function ($q) use ($request) {
            $q->whereHas('aduan', function ($query) use ($request) {
                $query->where('status', $request->status);
            });
        });

    $perPage = $request->input('per_page', 10);
    $perbaikan = $query->paginate($perPage);

    $periode = Periode::all();
    $status = \App\Http\Enums\Status::cases();

    if ($request->ajax()) {
        $html = view('sarpras.perbaikan.perbaikan_table', compact('perbaikan'))->render();
        return response()->json(['html' => $html]);
    }

    return view('sarpras.perbaikan.index', compact('breadcrumb', 'page', 'activeMenu', 'perbaikan', 'periode', 'status'));
}
}