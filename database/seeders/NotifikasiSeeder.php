<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notifikasi::create([
            'id_notifikasi' => 1,
            'pesan' => 'Status Laporan Aduan Berubah Jadi <b>HEBAT!</b>',
            'waktu_kirim' => now(),
            'id_user' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Notifikasi::create([
            'id_notifikasi' => 2,
            'pesan' => 'Status Laporan Aduan Berubah Jadi <b>MANTUL!</b>',
            'waktu_kirim' => now(),
            'id_user' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
