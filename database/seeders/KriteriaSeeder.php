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
                'bobot' => 0.15
            ],
            [
                'kode_kriteria' => 'UGS',
                'nama_kriteria' => 'Urgensi',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.23
            ],
            [
                'kode_kriteria' => 'BYA',
                'nama_kriteria' => 'Biaya Anggaran',
                'jenis_kriteria' => JenisKriteria::COST,
                'bobot' => 0.31
            ],
            [
                'kode_kriteria' => 'TKR',
                'nama_kriteria' => 'Tingkat Kerusakan',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.12,
            ],
            [
                'kode_kriteria' => 'LPB',
                'nama_kriteria' => 'Laporan Berulang',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.07
            ],
            [
                'kode_kriteria' => 'BTP',
                'nama_kriteria' => 'Bobot Pelapor',
                'jenis_kriteria' => JenisKriteria::BENEFIT,
                'bobot' => 0.12
            ],
        ];

        DB::table('kriteria')->insert($data);
    }
}