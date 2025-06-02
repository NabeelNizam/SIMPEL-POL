<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodeController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Periode',
            'list' => ['Home', 'Manajemen Data Periode']
        ];

        $page = (object) [
            'title' => 'Daftar periode yang terdaftar dalam sistem'
        ];

        $activeMenu = 'periode';

        // Query untuk periode
        $query = Periode::query();

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->where('kode_periode', 'like', "%{$request->search}%");
        }

        // Sorting
        $sortColumn = $request->sort_column ?? 'tanggal_mulai';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $perPage = $request->per_page ?? 10;
        $periode = $query->paginate($perPage);

        $periode->appends(request()->query());

        if ($request->ajax()) {
            $html = view('admin.periode.periode_table', compact('periode'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.periode.index', compact('breadcrumb', 'page', 'activeMenu', 'periode'));
    }

    public function create_ajax()
    {
        return view('admin.periode.create');
    }

    public function store_ajax(Request $request)
    {
        // Validasi input
        $rules = [
            'kode_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            //simpan periode
            $periode = Periode::create([
                'kode_periode' => $request->kode_periode,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Periode berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit_ajax(Periode $periode)
    {
        return view('admin.periode.edit', [
            'periode' => $periode
        ]);
    }


    public function update_ajax(Request $request, Periode $periode)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_periode' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'tanggal_mulai' => [
                    'required',
                    'date',
                ],
                'tanggal_selesai' => [
                    'required',
                    'date',
                    'after_or_equal:tanggal_mulai',
                ],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => 'weladalah ' . $validator->errors()
                ]);
            }

            $new_data = [
                'kode_periode' => $request->kode_periode,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ];
            $periode->update($new_data);
            return response()->json([
                'status' => true,
                'message' => 'Periode berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function show_ajax(Periode $periode)
    {
        return view('admin.periode.detail', [
            'periode' => $periode
        ]);
    }

    public function import_ajax()
    {
        return view('admin.periode.import');
    }

    public function confirm_ajax(Periode $periode)
    {
        return view('admin.periode.confirm', [
            'periode' => $periode
        ]);
    }

    public function remove_ajax(Periode $periode)
    {
        try {
            $periode->delete();
            return redirect()->route('admin.periode')->with('success', 'Periode berhasil dihapus.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
