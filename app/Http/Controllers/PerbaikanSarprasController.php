<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Fasilitas;
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
        $query = Fasilitas::with([
            'kategori',
            'ruangan',
            'inspeksi.perbaikan',
            'inspeksi.teknisi',
            'aduan' => function ($query) {
                $query->whereIn('status', ['sedang_diperbaiki', 'selesai']);
            }
        ])
            ->when($request->id_periode, function ($q) use ($request) {
                $q->whereHas('inspeksi', function ($query) use ($request) {
                    $query->where('id_periode', $request->id_periode);
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
