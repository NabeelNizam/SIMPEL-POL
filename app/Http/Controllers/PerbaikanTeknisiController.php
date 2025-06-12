<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Models\Fasilitas;
use App\Models\Notifikasi;
use App\Models\Perbaikan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $query = Perbaikan::with(['inspeksi', 'inspeksi.fasilitas', 'inspeksi.periode', 'inspeksi.fasilitas.aduan']);


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
        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->whereHas('inspeksi', function ($q) use ($request) {
                $q->whereHas('fasilitas', function ($q) use ($request) {
                    $q->where('nama_fasilitas', 'like', "%{$request->search}%");
                });
            });
        }

        // Filter status
        // if ($request->filled('status')) {
        //     $query->whereHas('inspeksi', function ($q) use ($request) {
        //         $q->whereHas('fasilitas', function ($q) use ($request) {
        //             $q->whereHas('aduan', function ($q) use ($request) {
        //                 $q->where('status', $request->status);
        //             });
        //         });
        //     });
        // }

        $query = $query->whereHas('inspeksi', function ($q) {
            $q->whereHas('fasilitas', function ($q) {
                $q->whereHas('aduan', function ($q) {
                    $q->where('status', Status::SEDANG_DIPERBAIKI->value);
                });
            });
        });


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
    public function cycle($id)
    {
        try {
            // Kode Eril
            $perbaikan = Perbaikan::findOrFail($id);
            
            // Bukan Kode Eril
            $inspeksi = $perbaikan->inspeksi;
            $fasilitas = Fasilitas::where('id_fasilitas', $inspeksi->id_fasilitas)->value('nama_fasilitas');
            
            // Kode Eril
            if ($perbaikan->teknisi_selesai) {
                $perbaikan->tanggal_selesai = null;
                
                // Bukan Kode Eril
                // Notifikasi ke sarpras
                Notifikasi::create([
                    'pesan' => 'Teknisi membatalkan status selesai Perbaikan untuk fasilitas <b class="text-red-500">' . $fasilitas . '</b>.',
                    'waktu_kirim' => now(),
                    'id_user' => $inspeksi->id_user_sarpras,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $pesan = 'Berhasil membatalkan menandai perbaikan sebagai selesai.';

            // Kode Eril
            } else {
                $perbaikan->tanggal_selesai = now();
                
                // Bukan Kode Eril
                // Notifikasi ke sarpras
                Notifikasi::create([
                    'pesan' => 'Teknisi telah menyelesaikan Perbaikan untuk fasilitas <b class="text-red-500">' . $fasilitas . '</b>. Silakan tinjau hasilnya.',
                    'waktu_kirim' => now(),
                    'id_user' => $inspeksi->id_user_sarpras,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $pesan = 'Berhasil menandai perbaikan sebagai selesai';
            }
            $perbaikan->update();

            
            
            return redirect()->back()->with('success', $pesan);
        } catch (\Exception $e) {
            Log::error('Gagal menyelesaikan tugas perbaikan. : ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menyelesaikan tugas perbaikan.']);
        }
    }
}
