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

    // Periksa keberadaan file
    foreach ($files as $role => $filename) {
        $filePath = "documents/{$role}/{$filename}";
        if (!Storage::disk('public')->exists($filePath)) {
            $files[$role] = null; // Set null jika file tidak ditemukan
        }
    }

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
    public function edit()
    {
        // Daftar file SOP berdasarkan role
        $roles = [
            'admin' => 'SOP_ADMIN.pdf',
            'sarpras' => 'SOP_SARPRAS.pdf',
            'pelapor' => 'SOP_PELAPOR.pdf',
            'teknisi' => 'SOP_TEKNISI.pdf',
        ];

        // Periksa keberadaan file
        $files = [];
        foreach ($roles as $role => $filename) {
            $filePath = "documents/{$role}/{$filename}";
            $files[$role] = Storage::disk('public')->exists($filePath) ? $filename : null;
        }

        // Kirim data ke view
        return view('admin.sop.edit', compact('files'));
    }
    public function update(Request $request)
{
    try {
        // Validasi input file
        $request->validate([
            'files.*' => 'nullable|file|mimes:pdf|max:2048', // Hanya menerima file PDF dengan ukuran maksimal 2MB
        ]);

        // Ambil daftar role dari input
        $roles = $request->input('roles', []);

        foreach ($roles as $role) {
            // Periksa apakah file diunggah untuk role ini
            if ($request->hasFile("files.$role")) {
                $file = $request->file("files.$role");

                // Tentukan nama file baru
                $filename = "SOP_" . strtoupper($role) . ".pdf";

                // Path file di storage
                $filePath = "documents/$role/$filename";

                // Hapus file lama jika ada
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                // Simpan file baru
                $file->storeAs("documents/$role", $filename, 'public');
            }
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.sop')->with('success', 'SOP berhasil diperbarui.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Tangani error validasi, termasuk file terlalu besar
        return redirect()->route('admin.sop')->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // Tangani error lainnya
        return redirect()->route('admin.sop')->with('error', 'Terjadi kesalahan saat memperbarui SOP. Silakan coba lagi.');
    }
}
public function delete($role)
{
    $filename = "SOP_" . strtoupper($role) . ".pdf";
    $filePath = "documents/$role/$filename";

    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);
        return redirect()->route('admin.sop')->with('success', "File SOP $role berhasil dihapus.");
    }

    return redirect()->route('admin.sop')->with('error', "File SOP $role tidak ditemukan.");
}
}