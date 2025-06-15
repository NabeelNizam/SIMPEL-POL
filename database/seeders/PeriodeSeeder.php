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
        Periode::updateOrCreate(
            [
                'id_periode' => 1
            ],
            [
                'kode_periode' => '2025-IV',
                'tanggal_mulai' => '2025-04-01',
                'tanggal_selesai' => '2025-04-30'
            ]
        );
        Periode::updateOrCreate(
            [
                'id_periode' => 2
            ],
            [
                'kode_periode' => '2025-V',
                'tanggal_mulai' => '2025-05-01',
                'tanggal_selesai' => '2025-05-30'
            ]
        );
        Periode::updateOrCreate(
            [
                'id_periode' => 3
            ],
            [
                'kode_periode' => '2025-VI',
                'tanggal_mulai' => '2025-06-01',
                'tanggal_selesai' => '2025-06-30'
            ]
        );
        Periode::updateOrCreate(
            [
                'id_periode' => 4
            ],
            [
                'kode_periode' => '2025-VII',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2025-07-31'
            ]
        );
    }
}
