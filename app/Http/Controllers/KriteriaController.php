<?php

namespace App\Http\Controllers;

use App\Models\KriteriaModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KriteriaController extends Controller
{
    public function list(Request $request)
    {
        $kriterias = KriteriaModel::select('id_kriteria', 'kode_kriteria', 'nama_kriteria', 'bobot');

        return DataTables::of($kriterias)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kriteria) {
                $btn = '<button onclick="modalAction(\''.url('/kriteria/' . $kriteria->kriteria_id . '/show').'\')" class="cursor-pointer px-1 py-1 rounded"><img src="'.asset('icons/crud/detail.svg').'" alt="Detail" class="w-5 h-5 inline"></button>';
                $btn .= '<button onclick="modalAction(\''.url('/kriteria/' . $kriteria->kriteria_id . '/edit').'\')" class="cursor-pointer px-1 py-1 rounded"><img src="'.asset('icons/crud/edit.svg').'" alt="Edit" class="w-5 h-5 inline"></button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
