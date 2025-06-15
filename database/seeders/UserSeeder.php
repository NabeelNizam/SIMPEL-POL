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
        $admin = User::create([
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
            'id_user' => $admin->id_user,
            'nip' => '9091909190',
        ]);

        // make teknisi
        $teknisi = User::create([
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
            'id_user' => $teknisi->id_user,
            'nip' => '9095909590',
        ]);

        // make sarpras
        $sarpras = User::create([
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
            'id_user' => $sarpras->id_user,
            'nip' => '9094909490',
        ]);

        // make mahasiswa
        $mahasiswa1 = User::create([
            'nama' => 'Aditya Atadewa',
            'email' => 'aditya@gmail.com',
            'email_verified_at' => now(),
            'username' => 'atadewa',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => Role::where('nama_role', 'MAHASISWA')->first()->id_role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Mahasiswa::create([
                'id_user' => $mahasiswa1->id_user,
                'nim' => '2341720174',
            ]);
 
        $mahasiswa2 = User::create([
            'nama' => 'Muhammad Erril',
            'email' => 'eril@gmail.com',
            'email_verified_at' => now(),
            'username' => 'user_erril',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => Role::where('nama_role', 'MAHASISWA')->first()->id_role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Mahasiswa::create([
                'id_user' => $mahasiswa2->id_user,
                'nim' => '2341720114',
            ]);
 
        $mahasiswa3 = User::create([
            'nama' => 'DhanilHaq',
            'email' => 'sopojarwo@gmail.com',
            'email_verified_at' => now(),
            'username' => 'user_dhanil',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => Role::where('nama_role', 'MAHASISWA')->first()->id_role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Mahasiswa::create([
                'id_user' => $mahasiswa3->id_user,
                'nim' => '2341720124',
            ]);
 
        $mahasiswa4 = User::create([
            'nama' => 'Nabeel Nizam',
            'email' => 'nabeel@gmail.com',
            'email_verified_at' => now(),
            'username' => 'user_nabeel',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => Role::where('nama_role', 'MAHASISWA')->first()->id_role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Mahasiswa::create([
                'id_user' => $mahasiswa4->id_user,
                'nim' => '2341720004',
            ]);
 
        $mahasiswa5 = User::create([
            'nama' => 'Khoirotun Nisa',
            'email' => 'iir@gmail.com',
            'email_verified_at' => now(),
            'username' => 'user_iir',
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => Role::where('nama_role', 'MAHASISWA')->first()->id_role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ]);
        Mahasiswa::create([
                'id_user' => $mahasiswa5->id_user,
                'nim' => '2341720001',
            ]);
 
        UserFactory::new()->count(20)->create();
    }
}
