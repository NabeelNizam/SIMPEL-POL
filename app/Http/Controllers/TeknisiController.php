<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeknisiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard Teknisi',
            'list' => ['Home', 'dashboard']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'home';
        
        $sedangLogin = Auth::user()->role->nama_role;
        $sedangLogin = strtolower(Auth::user()->role->nama_role);

        return view('teknisi.dashboard', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'sedangLogin' => $sedangLogin]);
    }

    public function SOPDownload($filename)
    {
        $role = 'teknisi'; // Bisa juga ambil dari auth jika dinamis
        $filePath = "documents/{$role}/{$filename}";

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        // Cegah akses ke file berbahaya
        if (
            str_contains($filename, '..') ||
            str_contains($filename, '/') ||
            str_contains($filename, '\\')
        ) {
            abort(403, 'Akses tidak sah.');
        }

        // Jalankan download menggunakan response()->download()
        return response()->download(storage_path("app/public/{$filePath}"));
    }
}