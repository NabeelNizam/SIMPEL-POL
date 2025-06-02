<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

// Controller untuk menguji export PDF menggunakan Sheet
class TestExport extends Controller
{
    public function pedeef()
    {
        // Ambil data mahasiswa berdasarkan role 'mahasiswa'
        $roleMahasiswa = Role::query()->where('nama_role', 'mahasiswa')->first();
        $mahasiswa = User::query()->where('id_role', $roleMahasiswa->id_role)->get();
        $headers = ['nama', 'nim'];

        // Data disusun (map) supaya formatnya sesuai dengan yang diinginkan
        // ini jadi: ['nama' => 'nama', 'nim' => 'nim']
        $data = $mahasiswa->map(function ($item) {
            return [
                'nama' => $item->nama,
                'nim' => $item->getIdentifier(),
            ];
        })->toArray();

        // Membuat instance dari Sheet dengan data yang sudah disiapkan
        // Judul, teks, footer, header, data, dan nama file
        $sheet = Sheet::make(
            [
                'title' => 'Data Mahasiswa',
                'text' => 'Berikut adalah daftar mahasiswa yang terdaftar di sistem.',
                'footer' => 'Dibuat oleh Nabeela',
                'header' => $headers,
                'data' => $data,
                'filename' => 'data_mahasiswa'
            ]
        );

        return $sheet->toPdf();
        // Untuk mengunduh sebagai file Excel, bisa menggunakan:
        // return $sheet->toXls();
    }
}
