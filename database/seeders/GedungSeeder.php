<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gedung::updateOrCreate(
            [
                'id_gedung' => 1,
            ],
            [
                'kode_gedung' => 'GTI',
                'nama_gedung' => 'Gedung Teknologi Informasi',
                'id_jurusan' => 1
            ]
        );
        Gedung::updateOrCreate(
            [
                'id_gedung' => 2,
            ],
            [
                'kode_gedung' => 'GTS',
                'nama_gedung' => 'Gedung Teknik Sipil',
                'id_jurusan' => 1
            ]
        );
        Gedung::updateOrCreate(
            [
                'id_gedung' => 3,
            ],
            [
                'kode_gedung' => 'GTM',
                'nama_gedung' => 'Gedung Teknik Mesin',
                'id_jurusan' => 1
            ]
        );
        Gedung::updateOrCreate(
            [
                'id_gedung' => 4,
            ],
            [
                'kode_gedung' => 'GTK',
                'nama_gedung' => 'Gedung Teknik Kimia',
                'id_jurusan' => 1
            ]
        );
        Gedung::updateOrCreate(
            [
                'id_gedung' => 5,
            ],
            [
                'kode_gedung' => 'GTA',
                'nama_gedung' => 'Gedung Teknik Akuntasi',
                'id_jurusan' => 1
            ]
        );
        
    }
}
