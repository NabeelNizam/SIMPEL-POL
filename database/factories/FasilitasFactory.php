<?php

namespace Database\Factories;

use App\Http\Enums\Kondisi;
use App\Http\Enums\Urgensi;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Kategori;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fasilitas>
 */
class FasilitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $random = fake()->randomElement(['Meja', 'PC', 'Papan Tulis', 'Kursi', 'Pintu', 'Proyektor']);
        $elements =['Elektronik'=>['Proyektor', 'Kipas Angin', 'AC'],
            'Furniture'=>['Meja', 'Kursi', 'Papan Tulis'],
            'Teknologi'=>['PC', 'Monitor'],
            'Keamanan dan Keselamatan'=>['CCTV','APAR']
        ];
        $kategori = Kategori::all()->random();
        $nama_fasilitas = fake()->randomElement($elements[$kategori->nama_kategori]);
        return [
            'id_kategori' => $kategori->id_kategori,
            'nama_fasilitas' => $nama_fasilitas,
            'kode_fasilitas' => $kategori->kode_kategori . '-'. fake()->unique()->numberBetween(1000, 9999),
            'kondisi' => Kondisi::LAYAK,
            'urgensi' => fake()->randomElement(Urgensi::cases()),
            'id_periode' => Periode::find(1)->id_periode,
            'id_ruangan' => fake()->randomElement(Ruangan::pluck('id_ruangan')),
            'deskripsi' => fake()->paragraph(2),
            'foto_fasilitas' => 'storage/uploads/img/foto_fasilitas/'. $nama_fasilitas . '.jpg',
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Fasilitas $fasilitas) {
        });
    }
}
