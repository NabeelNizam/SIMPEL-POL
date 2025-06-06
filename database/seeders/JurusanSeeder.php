<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::create([
            'id_jurusan' => 1,
            'kode_jurusan' => 'TI',
            'nama_jurusan' => 'Teknologi Informasi',
        ]);
    }
}
