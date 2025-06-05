<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class SarprasPenugasanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Penugasan',
            'list' => ['Home', 'Manajemen Data Fasilitas']
        ];

        $page = (object) [
            'title' => 'Daftar penugasan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penugasan';

    

        return view('sarpras.penugasan.index' , compact('breadcrumb', 'page', 'activeMenu'));
    }
}
