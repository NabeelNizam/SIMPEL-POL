<?php

use App\Http\Controllers\Controller;
use App\Models\KriteriaModel;
use Illuminate\Http\Request;

class BobotSarprasController extends Controller
{
    public function bobot()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Bobot Prioritas Perbaikan',
            'list'  => ['Home']
        ];

        $activeMenu = '';

        $kriteria = KriteriaModel::all();

        return view('sarpras.bobot.index', ['breadcrumb' => $breadcrumb, 'kriteria' => $kriteria, 'activeMenu' => $activeMenu]);
    }
}