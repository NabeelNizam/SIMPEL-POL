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
        $months = [
            'I' => ['2024-01-01', '2024-01-31'],
            'II' => ['2024-02-01', '2024-02-29'],
            'III' => ['2024-03-01', '2024-03-31'],
            'IV' => ['2024-04-01', '2024-04-30'],
            'V' => ['2024-05-01', '2024-05-3'],
            'VI' => ['2024-06-01', '2024-06-30'],
            'VII' => ['2024-07-01', '2024-07-31'],
            'VIII' => ['2024-08-01', '2024-08-31'],
            'IX' => ['2024-09-01', '2024-09-30'],
            'X' => ['2024-10-01', '2024-10-31'],
            'XI' => ['2024-11-01', '2024-11-30'],
            'XII' => ['2024-12-01', '2024-12-31'],
        ];

        foreach ($months as $kode => $dates) {
            Periode::create([
                'kode_periode' => "2024-{$kode}",
                'tanggal_mulai' => $dates[0],
                'tanggal_selesai' => $dates[1],
            ]);
        }
    }
}
