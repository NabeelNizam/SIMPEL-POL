<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Home']
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

    public function SOPDownload($filename)
    {
        // Daftar file SOP yang tersedia
        $allowedFiles = [
            'sop-perbaikan-fasilitas' => [
                'filename' => 'SOP_Perbaikan_Fasilitas.pdf',
                'original_name' => 'SOP Perbaikan Fasilitas.pdf'
            ]
        ];

        // Cek apakah file yang diminta valid
        if (!array_key_exists($filename, $allowedFiles)) {
            abort(404, 'File tidak ditemukan');
        }

        $fileInfo = $allowedFiles[$filename];
        $filePath = 'documents/sop/' . $fileInfo['filename'];

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di server');
        }

        // Log download activity (optional)
        Log::info('SOP Downloaded', [
            'user_id' => auth()->id(),
            'filename' => $fileInfo['original_name'],
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Return file for download
        return response()->download(
            storage_path('app/public/' . $filePath),
            $fileInfo['original_name']
        );
    }

}