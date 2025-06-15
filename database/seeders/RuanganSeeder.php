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
                'kode_ruangan' => 'RT_01',
                'nama_ruangan' => 'Ruang Teori 01',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 2
            ],
            [
                'kode_ruangan' => 'RT_02',
                'nama_ruangan' => 'Ruang Teori 02',
                'id_lantai' => 6
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 3
            ],
            [
                'kode_ruangan' => 'RT_03',
                'nama_ruangan' => 'Ruang Teori 03',
                'id_lantai' => 6
            ]
        );
        
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 4
            ],
            [   
                'kode_ruangan' => 'LSI_01',
                'nama_ruangan' => 'Laboratorium Sistem Informasi 01',
                'id_lantai' => 7
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 5
            ],
            [   
                'kode_ruangan' => 'LSI_02',
                'nama_ruangan' => 'Laboratorium Sistem Informasi 02',
                'id_lantai' => 7
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 6
            ],
            [   
                'kode_ruangan' => 'RD_01',
                'nama_ruangan' => 'Ruang Dosen 01',
                'id_lantai' => 7
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 7
            ],
            [   
                'kode_ruangan' => 'LKJ_01',
                'nama_ruangan' => 'Laboratorium Komputer & Jaringan 01',
                'id_lantai' => 8
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 8
            ],
            [   
                'kode_ruangan' => 'LPR_01',
                'nama_ruangan' => 'Laboratorium Pemrograman 01',
                'id_lantai' => 8
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 9
            ],
            [   
                'kode_ruangan' => 'LPY_04',
                'nama_ruangan' => 'Laboratorium Proyek 01',
                'id_lantai' => 8
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 10
            ],
            [   
                'kode_ruangan' => 'RT_10',
                'nama_ruangan' => 'Ruang Teori 10',
                'id_lantai' => 9
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 11
            ],
            [   
                'kode_ruangan' => 'RT_11',
                'nama_ruangan' => 'Ruang Teori 11',
                'id_lantai' => 9
            ]
        );
        Ruangan::updateOrCreate(
            [
                'id_ruangan' => 12
            ],
            [   
                'kode_ruangan' => 'RT_12',
                'nama_ruangan' => 'Ruang Teori 12',
                'id_lantai' => 9 
            ]
        );
    }
}
