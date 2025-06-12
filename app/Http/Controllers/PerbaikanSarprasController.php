<?php

namespace App\Http\Controllers;

use App\Http\Enums\Status;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Notifikasi;
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
            'title' => 'Perbaikan Fasilitas',
            'list' => ['Home', 'Perbaikan Fasilitas']
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
                $query->where('status', Status::SEDANG_DIPERBAIKI->value);
            }
        ])
            ->whereHas('aduan', function ($query) {
                $query->where('status', Status::SEDANG_DIPERBAIKI->value);
            })
            ->when($request->id_periode, function ($q) use ($request) {
                $q->whereHas('inspeksi', function ($query) use ($request) {
                    $query->where('id_periode', $request->id_periode);
                });
            });



        if ($request->search) {
            $query->where('nama_fasilitas', 'like', "%{$request->search}%");
        }

        // Filter berdasarkan periode
        if ($request->id_periode) {
            $query->whereHas('aduan', function ($q) use ($request) {
                $q->where('id_periode', $request->id_periode);
            });
        }
        // Filter berdasarkan status
        if ($request->status) {
            $query->whereHas('aduan', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $perPage = $request->input('per_page', 10);
        $perbaikan = $query->paginate($perPage);

        $periode = Periode::all();
        $status  = array_filter(Status::cases(), function ($case) {
            return in_array($case->value, ['SEDANG_DIPERBAIKI', 'SELESAI']);
        });;

        if ($request->ajax()) {
            $html = view('sarpras.perbaikan.perbaikan_table', compact('perbaikan'))->render();
            return response()->json(['html' => $html]);
        }

        return view('sarpras.perbaikan.index', compact('breadcrumb', 'page', 'activeMenu', 'perbaikan', 'periode', 'status'));
    }

    public function show_perbaikan($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor', 'inspeksi.teknisi', 'inspeksi.perbaikan'])->withCount('aduan')->findOrFail($id);
        // $query = Aduan::getTanggalPerbaikanAttribute($fasilitas->id_fasilitas, $fasilitas->inspeksi->last()->tanggal_selesai);
        // dd($query->toSql(), $query->getBindings());
        $inspeksi = $fasilitas->inspeksi->first();

        return view('sarpras.perbaikan.detail', ['fasilitas' => $fasilitas, 'inspeksi' => $inspeksi]);
    }

    public function confirm_approval($id)
    {
        return view('sarpras.perbaikan.confirm')->with([
            'id' => $id
        ]);
    }


    public function approve($id)
    {
        try {
            $aduan = Aduan::where('id_fasilitas', $id)->where('status', Status::SEDANG_DIPERBAIKI->value)->get();
            $fasilitas = Fasilitas::where('id_fasilitas', $id)->value('nama_fasilitas');
            foreach ($aduan as $a) {
                $a->update(['status' => Status::SELESAI->value]);

                // Notifikasi ke pelapor (versi panjang)
                Notifikasi::create([
                    'pesan' => 'Fasilitas <b class="text-red-500">' . $fasilitas . '</b> yang Anda laporkan telah selesai diperbaiki. Terima kasih atas partisipasinya.',
                    'waktu_kirim' => now(),
                    'id_user' => $a->pelapor->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->back()->with('success', 'Berhasil Perbaharui Status Aduan Fasilitas.');
        } catch (\Exception $e) {
            Log::error('Gagal Menandai sebagai Selesai: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal Menandai sebagai Selesai.']);
        }
    }
}
