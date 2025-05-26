<?php

namespace App\Http\Controllers;

use App\Models\KriteriaModel;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    public function bobot()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Bobot Prioritas Perbaikan',
            'list'  => ['Home']
        ];

        $kriteria = KriteriaModel::all();

        return view('sarpras.bobot.index', ['breadcrumb' => $breadcrumb, 'kriteria' => $kriteria]);
    }
}