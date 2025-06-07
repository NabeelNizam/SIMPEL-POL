<?php

namespace Database\Seeders;

use App\Models\Inspeksi;
use App\Models\Perbaikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspeksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inspeksi::factory()->count(100)->create();
    }
}
