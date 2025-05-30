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
            'nama_kategori' => 'elektronik',
            'kode_kategori' => 'ELK'
        ]);
        Kategori::create([
            'nama_kategori' => 'furniture',
            'kode_kategori' => 'FRN'
        ]);
        Kategori::create([
            'nama_kategori' => 'buku',
            'kode_kategori' => 'BUK'
        ]);
    }
}
