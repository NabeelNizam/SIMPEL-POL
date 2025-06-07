<?php

namespace Database\Seeders;

use App\Http\Enums\Kondisi;
use App\Http\Enums\Status;
use App\Http\Enums\Urgensi;
use App\Models\Aduan;
use App\Models\Biaya;
use App\Models\Fasilitas;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\Ruangan;
use App\Models\UmpanBalik;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fasilitas::factory()->count(10)->create();
    }
}
