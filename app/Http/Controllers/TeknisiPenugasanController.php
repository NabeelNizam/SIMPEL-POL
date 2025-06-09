<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Perbaikan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            ->firstOrFail();

        $statusAduan = $aduan->status->value ?? '-';

        // Return view edit
        return view('teknisi.penugasan.edit', compact('inspeksi', 'fasilitas', 'biaya', 'statusAduan'));
    }

    public function update_ajax(Request $request, Inspeksi $inspeksi)
    {
        try {
            // Validasi input
            $validation = Validator::make($request->all(), [
                'tingkat_kerusakan' => ['required', Rule::in(['PARAH', 'SEDANG', 'RINGAN']),],
                'deskripsi_pekerjaan' => ['required', 'string'],
                'biaya.*.keterangan' => ['required', 'string'],
                'biaya.*.besaran' => ['required'],
            ]);
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 422);
                // throw new ValidationException($validation);
            }

            // Update tingkat kerusakan
            $inspeksi->tingkat_kerusakan = $request->tingkat_kerusakan;

            // Update deskripsi inspeksi
            $inspeksi->deskripsi = $request->deskripsi_pekerjaan;


            // Update rincian anggaran
            if ($request->has('biaya')) {
                # code...
                $inspeksi->biaya()->delete(); // Hapus semua biaya lama
                foreach ($request->biaya as $biaya) {
                    $besaran =  preg_replace('/[^0-9]/', '', $biaya['besaran']);

                    Biaya::create([
                        'id_inspeksi' => $inspeksi->id_inspeksi,
                        'keterangan' => $biaya['keterangan'],
                        'besaran' => (int) $besaran,
                    ]);
                }
            }
            $inspeksi->update();

            // Redirect atau response JSON
            return response()->json(['message' => 'Data berhasil diperbarui.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'besaran' => (int) preg_replace('/[^0-9]/', '',$request->biaya[1]['besaran'])], 422);
        }
    }
}
