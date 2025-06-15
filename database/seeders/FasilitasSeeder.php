<?php

namespace Database\Seeders;

use App\Http\Enums\Kondisi;
use App\Http\Enums\Status;
use App\Http\Enums\Urgensi;
use App\Models\Aduan;
use App\Models\Biaya;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
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
        $fasilitas =  Fasilitas::factory()->count(20)->create();

        // $fasilitas = [];

        // $fasilitas[] = Fasilitas::create([
        //     'id_kategori' => 1,
        //     'nama_fasilitas' => 'AC Panasonic 2 PK',
        //     'kode_fasilitas' => 'ELK-',
        //     'kondisi' => Kondisi::LAYAK,
        //     'urgensi' => Urgensi::DARURAT,
        //     'id_periode' => Periode::find(1)->id_periode,
        //     'id_ruangan' => fake()->randomElement(Ruangan::pluck('id_ruangan')),
        //     'foto_fasilitas' => fake()->imageUrl(640, 480, 'business', true, 'Fasilitas', true),
        //     'deskripsi' => fake()->paragraph(2),
        // ]);

        foreach ($fasilitas as $item) {
            if(fake()->boolean()){
                continue;
            }
            Inspeksi::factory()->create([
                'id_fasilitas' => $item->id_fasilitas,
            ]);
        }
    }
}
