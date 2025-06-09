<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Models\Fasilitas;
use App\Models\Perbaikan;
use App\Models\Periode;
use Illuminate\Http\Request;

class PerbaikanTeknisiController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Perbaikan',
            'list' => ['Home', 'Perbaikan']
        ];

        $page = (object) [
            'title' => 'Perbaikan'
        ];

        $activeMenu = 'perbaikan';

        // Query untuk mengambil data melalui tabel Aduan
        // $query = Fasilitas::query();
        $query = Perbaikan::with(['inspeksi', 'inspeksi.fasilitas', 'inspeksi.periode']);

        // Filter periode
        // if ($request->id_periode) {
        //     $query->whereHas('inspeksi', function ($q) use ($request) {
        //         $q->whereHas('perbaikan', function ($q) use ($request) {
        //             $q->where('id_periode', $request->id_periode);
        //         });
        //     });
        // }else{
        //     // throw new \Exception('Periode tidak ditemukan');
        // }

        // filter periode
        if ($request->filled('id_periode')) {
            $query->whereHas('inspeksi', function ($q) use ($request) {
                $q->where('id_periode', $request->id_periode);
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->whereHas('inspeksi', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }



        $perPage = $request->input('per_page', 10);
        $perbaikan = $query->paginate($perPage);

        $periode = Periode::all();
        $status = Status::cases();

        if ($request->ajax()) {
            $html = view('teknisi.perbaikan.perbaikan_table', compact('perbaikan'))->render();
            return response()->json(['html' => $html]);
        }

        return view('teknisi.perbaikan.index', compact('breadcrumb', 'page', 'activeMenu', 'perbaikan', 'periode', 'status'));
    }
    public function show($id)
    {
        try {
            $perbaikan = Perbaikan::with(['inspeksi', 'inspeksi.fasilitas', 'inspeksi.fasilitas.aduan', 'inspeksi.periode', 'inspeksi.biaya'])->findOrFail($id);
            $inspeksi = $perbaikan->inspeksi;
            $fasilitas = $inspeksi->fasilitas;
            // dd($inspeksi);
            $statusAduan = $inspeksi->status_aduan->value ?? '-';
            $biaya = $inspeksi->biaya;
            return view('teknisi.perbaikan.detail', compact('perbaikan', 'inspeksi', 'fasilitas', 'statusAduan', 'biaya'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
