<?php

namespace App\Http\Controllers;

use App\Models\KriteriaModel;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Home', 'Dashboard']
        ];

        $page = (object) [
            'title' => 'Beranda Sarana Prasarana'
        ];

        $activeMenu = 'home';

        return view('sarpras.dashboard', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu
        ]);
    }
    
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
