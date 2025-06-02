<?php

namespace Database\Seeders;

use App\Models\Lantai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LantaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lantai::updateOrCreate(
            [
                'id_lantai' => 1
            ],
            [
                'nama_lantai' => 'LT.1',
                'id_gedung' => 2
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 2
            ],
            [
                'nama_lantai' => 'LT.2',
                'id_gedung' => 2
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 3
            ],
            [
                'nama_lantai' => 'LT.3',
                'id_gedung' => 2
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 4
            ],
            [
                'nama_lantai' => 'LT.4',
                'id_gedung' => 2
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 5
            ],
            [
                'nama_lantai' => 'LT.5 Timur',
                'id_gedung' => 2
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 6
            ],
            [
                'nama_lantai' => 'LT.5 Barat',
                'id_gedung' => 1
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 7
            ],
            [
                'nama_lantai' => 'LT.6',
                'id_gedung' => 1
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 8
            ],
            [
                'nama_lantai' => 'LT.7',
                'id_gedung' => 1
            ]
        );
        Lantai::updateOrCreate(
            [
                'id_lantai' => 9
            ],
            [
                'nama_lantai' => 'LT.8',
                'id_gedung' => 1
            ]
        );
    }
}
