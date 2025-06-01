<?php

namespace App\Http\Controllers;

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
}
