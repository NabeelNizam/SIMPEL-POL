<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Periode::create([
            'kode_periode' => '2024-IV',
            'tanggal_mulai' => '2024-04-01',
            'tanggal_selesai' => '2024-04-30'
        ]);
        Periode::create([
            'kode_periode' => '2024-V',
            'tanggal_mulai' => '2024-05-01',
            'tanggal_selesai' => '2024-05-31'
        ]);
        Periode::create([
            'kode_periode' => '2025-VI',
            'tanggal_mulai' => '2025-06-01',
            'tanggal_selesai' => '2025-06-31'
        ]);

    }
}
