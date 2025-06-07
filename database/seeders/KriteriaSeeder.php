<?php

namespace Database\Seeders;

use App\Http\Enums\JenisKriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kode_kriteria' => 'USC',
                'nama_kriteria' => 'User Count',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.1
            ],
            [
                'kode_kriteria' => 'UGS',
                'nama_kriteria' => 'Urgensi',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.25
            ],
            [
                'kode_kriteria' => 'BYA',
                'nama_kriteria' => 'Biaya Anggaran',
                'jenis_kriteria' => JenisKriteria::COST,
                'bobot' => 0.25
            ],
            [
                'kode_kriteria' => 'TKR',
                'nama_kriteria' => 'Tingkat Kerusakan',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.2,
            ],
            [
                'kode_kriteria' => 'WKT',
                'nama_kriteria' => 'Waktu',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.2
            ],
        ];

        DB::table('kriteria')->insert($data);
    }
}