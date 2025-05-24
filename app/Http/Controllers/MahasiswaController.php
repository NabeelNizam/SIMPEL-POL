<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Beranda mahasiswa',
            'list' => ['Home', 'Beranda']
        ];

        $page = (object) [
            'title' => 'Beranda mahasiswa'
        ];

        $activeMenu = 'home';

        return view('mahasiswa.dashboard', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu
        ]);
    }
}