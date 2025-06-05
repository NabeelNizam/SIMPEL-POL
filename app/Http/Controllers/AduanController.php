<?php

namespace App\Http\Controllers;

use App\Http\Helpers\AlternativeDTO;
use App\Http\Helpers\CopelandAggregator;
use App\Http\Helpers\PrometheeCalculator;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Kategori;
use App\Models\Perbaikan;
use App\Models\UmpanBalik;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function pengaduan(Request $request, CopelandAggregator $copelandAggregator)
    {
        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Home', 'Laporan Pengaduan']
        ];

        $page = (object) [
            'title' => 'Daftar Pengaduan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengaduan';

        $bobot = ['user_count' => 0.5, 'urgensi' => 0.5];

        // urgensi dictionary
        $urgensiDictionary = [
            'DARURAT' => 3,
            'PENTING' => 2,
            'BIASA' => 1
        ];

        // filter berdasarkan role mahasiswa
        $targetRole = 'MAHASISWA'; // Role yang ingin difilter
        $query = Fasilitas::query()
            ->with(['aduan.pelapor.role'])
            ->whereHas('aduan.pelapor.role', function ($query) use ($targetRole) {
                $query->where('nama_role', $targetRole);
            })
            ->withCount([
                'aduan as aduan_count' => function ($query) use ($targetRole) {
                    $query->whereHas('pelapor.role', function ($query) use ($targetRole) {
                        $query->where('nama_role', $targetRole);
                    });
                }
            ])
            ->get()
            ->map(function ($fasilitas) use ($urgensiDictionary) {
                return [
                    'name' => $fasilitas->id_fasilitas,
                    'user_count' => $fasilitas->aduan_count,
                    'urgensi' => $urgensiDictionary[$fasilitas->urgensi->value], // or ->urgensi if string
                ];
            });

        $alternatives = [];

        foreach ($query as $item) {
            $alternatives[] = new AlternativeDTO(
                $item['name'],
                [
                    'user_count' => $item['user_count'],
                    'urgensi' => $item['urgensi']
                ]
            );
        }
        // dd($alternatives);

        $promethee_mahasiswa = PrometheeCalculator::calculatePromethee($alternatives, $bobot);
        // dd($promethee_mahasiswa);

        // filter berdasarkan role dosen
        $targetRole = 'DOSEN'; // Role yang ingin difilter
        $query = Fasilitas::query()
            ->with(['aduan.pelapor.role'])
            ->whereHas('aduan.pelapor.role', function ($query) use ($targetRole) {
                $query->where('nama_role', $targetRole);
            })
            ->withCount([
                'aduan as aduan_count' => function ($query) use ($targetRole) {
                    $query->whereHas('pelapor.role', function ($query) use ($targetRole) {
                        $query->where('nama_role', $targetRole);
                    });
                }
            ])
            ->get()
            ->map(function ($fasilitas) use ($urgensiDictionary) {
                return [
                    'name' => $fasilitas->id_fasilitas,
                    'user_count' => $fasilitas->aduan_count,
                    'urgensi' => $urgensiDictionary[$fasilitas->urgensi->value], // or ->urgensi if string
                ];
            });
        $alternatives = [];
        foreach ($query as $item) {
            $alternatives[] = new AlternativeDTO(
                $item['name'],
                [
                    'user_count' => $item['user_count'],
                    'urgensi' => $item['urgensi']
                ]
            );
        }

        $promethee_dosen = PrometheeCalculator::calculatePromethee($alternatives, $bobot);

        // filter berdasarkan role tendik
        $targetRole = 'TENDIK'; // Role yang ingin difilter
        $query = Fasilitas::query()
            ->with(['aduan.pelapor.role'])
            ->whereHas('aduan.pelapor.role', function ($query) use ($targetRole) {
                $query->where('nama_role', $targetRole);
            })
            ->withCount([
                'aduan as aduan_count' => function ($query) use ($targetRole) {
                    $query->whereHas('pelapor.role', function ($query) use ($targetRole) {
                        $query->where('nama_role', $targetRole);
                    });
                }
            ])
            ->get()
            ->map(function ($fasilitas) use ($urgensiDictionary) {
                return [
                    'name' => $fasilitas->id_fasilitas,
                    'user_count' => $fasilitas->aduan_count,
                    'urgensi' => $urgensiDictionary[$fasilitas->urgensi->value], // or ->urgensi if string
                ];
            });
        $alternatives = [];

        foreach ($query as $item) {
            $alternatives[] = new AlternativeDTO(
                $item['name'],
                [
                    'user_count' => $item['user_count'],
                    'urgensi' => $item['urgensi']
                ]
            );
        }
        $promethee_tendik = PrometheeCalculator::calculatePromethee($alternatives, $bobot);

        $normalized_pred_gdss = AlternativeDTO::normalizeAlternatives(
            [
                $promethee_mahasiswa,
                $promethee_dosen,
                $promethee_tendik
            ]
        );
        // dd($promethee_mahasiswa, $promethee_dosen, $promethee_tendik);

        $copeland = new CopelandAggregator();
        $copeland->alternatives =$normalized_pred_gdss;

        dd($promethee_mahasiswa, $promethee_dosen, $promethee_tendik, $normalized_pred_gdss, $copeland->run());
// return [$promethee_mahasiswa, $promethee_dosen, $promethee_tendik, $normalized_pred_gdss, $copeland->run()];
        // gdss





        // $query->orderBy('aduan_count', 'desc');

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

        // return view('sarpras.pengaduan.index', compact('breadcrumb', 'page', 'activeMenu', 'pengaduan', 'kategori'));
    }

    // Detail Fasilitas & Laporan Pengaduan nya
    public function show_pengaduan($id)
    {
        $fasilitas = Fasilitas::with(['kategori', 'ruangan', 'aduan.pelapor'])->withCount('aduan')->findOrFail($id);
        // dd($fasilitas);
        return view('sarpras.pengaduan.detail', ['fasilitas' => $fasilitas]);
    }
}
