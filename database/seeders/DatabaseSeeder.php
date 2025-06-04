<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            JurusanSeeder::class,
            PeriodeSeeder::class,
            UserSeeder::class,
            KategoriSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuanganSeeder::class,
            FasilitasSeeder::class,
            KriteriaSeeder::class
        ]);
    }
}
