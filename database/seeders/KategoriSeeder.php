<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'nama_kategori' => 'Elektronik',
            'kode_kategori' => 'ELK'
        ]);
        Kategori::create([
            'nama_kategori' => 'Furniture',
            'kode_kategori' => 'FTR'
        ]);
        Kategori::create([
            'nama_kategori' => 'Teknologi',
            'kode_kategori' => 'TKL'
        ]);
        Kategori::create([
            'nama_kategori' => 'Keamanan dan Keselamatan',
            'kode_kategori' => 'KDK'
        ]);
    }
}
