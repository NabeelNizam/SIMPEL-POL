<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // make admin
        User::create([
            'id_user' => 1,
            'nama' => 'Nabeela Nizama',
            'email' => 'nabeela@lucu.com',
            'email_verified_at' => now(),
            'username' => 'nabeela',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => 2,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Pegawai::create([
            'id_user' => 1,
            'nip' => '9091',
        ]);

        // make teknisi
        User::create([
            'id_user' => 2,
            'nama' => 'Atta Halilin',
            'email' => 'atta@lucu.com',
            'email_verified_at' => now(),
            'username' => 'halin',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => 3,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Pegawai::create([
            'id_user' => 2,
            'nip' => '9095',
        ]);

        // make sarpras
        User::create([
            'id_user' => 3,
            'nama' => 'Orochimaru',
            'email' => 'oo@lucu.com',
            'email_verified_at' => now(),
            'username' => 'orochi',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => 4,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Pegawai::create([
            'id_user' => 3,
            'nip' => '9094',
        ]);

        User::create([
            'id_user' => 4,
            'nama' => 'Garaga',
            'email' => 'garaga@lucu.com',
            'email_verified_at' => now(),
            'username' => 'garaga',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => Role::where('nama_role', 'MAHASISWA')->first()->id_role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Mahasiswa
            ::create([
                'id_user' => 3,
                'nim' => '9093',
            ]);


        // make pelapor
        UserFactory::new()->count(20)->create();
    }
}
