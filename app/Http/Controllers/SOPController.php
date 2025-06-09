<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SOPController extends Controller
{
    public function index(Request $request)
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Manajemen SOP',
            'list' => ['Home', 'SOP']
        ];

        // Informasi halaman
        $page = (object) [
            'title' => 'Daftar SOP yang terdaftar dalam sistem'
        ];

        // Menu aktif
        $activeMenu = 'sop';
        $selectedRole = $request->input('selectedRole', 'admin');
        // Daftar file SOP berdasarkan role (statis)
        $files = [
            'admin' => 'SOP_ADMIN.pdf',
            'sarpras' => 'SOP_SARPRAS.pdf',
            'pelapor' => 'SOP_PELAPOR.pdf',
            'teknisi' => 'SOP_TEKNISI.pdf',
        ];
    
        // Mengirim data ke view
        return view('admin.sop.index', [
            'adminFile' => $files['admin'],
            'sarprasFile' => $files['sarpras'],
            'pelaporFile' => $files['pelapor'],
            'teknisiFile' => $files['teknisi'],
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'selectedRole' => $selectedRole,
        ]);
    }
    public function SOPDownload($role, $filename)
    {
        // Daftar file SOP berdasarkan role (statis)
        $files = [
            'admin' => 'SOP_ADMIN.pdf',
            'sarpras' => 'SOP_SARPRAS.pdf',
            'pelapor' => 'SOP_PELAPOR.pdf',
            'teknisi' => 'SOP_TEKNISI.pdf',
        ];

        // Validasi role
        if (!array_key_exists($role, $files)) {
            abort(404, 'Role tidak valid.');
        }

        // Validasi nama file
        if ($files[$role] !== $filename) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        // Path file berdasarkan role
        $filePath = "documents/{$role}/{$filename}";

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        // Jalankan download menggunakan response()->download()
        return response()->download(storage_path("app/public/{$filePath}"));
    }
}