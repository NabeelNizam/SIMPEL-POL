<?php

namespace App\Http\Controllers;

class SOPController extends Controller
{
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Manajemen SOP',
            'list' => ['Home', 'SOP']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'sop';

        return view('admin.sop.index', compact(
            'breadcrumb',
            'page',
            'activeMenu'));
    }
}