<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id_role' => 2,
            'kode_role' => 'ADM',
            'nama_role' => 'ADMIN',
            'kode_role' => 'ADM',
        ]);
        Role::create([
            'id_role' => 1,
            'kode_role' => 'MHS',
            'nama_role' => 'MAHASISWA',
            'kode_role' => 'MHS',
        ]);
        Role::create([
           'id_role' => 3,
            'kode_role' => 'TEK',
           'nama_role' => 'TEKNISI',
           'kode_role' => 'TEK',
        ]);
        Role::create([
            'id_role' => 4,
            'kode_role' => 'SPR',
            'nama_role' => 'SARPRAS',
            'kode_role' => 'SAR',
        ]);
        Role::create([
            'id_role' => 5,
            'kode_role' => 'DOS',
            'nama_role' => 'DOSEN',
            'kode_role' => 'DSN',
        ]);
        Role::create([
            'id_role' => 6,
            'kode_role' => 'TEN',
            'nama_role' => 'TENDIK',
            'kode_role' => 'TND',
        ]);
    }
}
