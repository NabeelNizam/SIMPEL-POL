<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 1
            ],
            [
                'kode_ruangan' => 'RT01',
                'nama_ruangan' => 'Ruang Teori 01',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 2
            ],
            [
                'kode_ruangan' => 'RT02',
                'nama_ruangan' => 'Ruang Teori 02',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 3
            ],
            [
                'kode_ruangan' => 'RT03',
                'nama_ruangan' => 'Ruang Teori 03',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 4
            ],
            [
                'kode_ruangan' => 'RT04',
                'nama_ruangan' => 'Ruang Teori 04',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 5
            ],
            [
                'kode_ruangan' => 'RT05',
                'nama_ruangan' => 'Ruang Teori 05',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 6
            ],
            [
                'kode_ruangan' => 'RT06',
                'nama_ruangan' => 'Ruang Teori 06',
                'id_lantai' => 6
            ]
        );
    }
}
