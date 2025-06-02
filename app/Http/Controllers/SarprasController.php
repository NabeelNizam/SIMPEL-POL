<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SarprasController extends Controller
{
   public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard Sarana Prasarana',
            'list' => ['Home', 'dashboard']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dashboard';


        return view('sarpras.dashboard', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

}
