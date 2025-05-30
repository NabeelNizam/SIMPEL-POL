<?php

namespace Database\Factories;

use App\Http\Enums\Kondisi;
use App\Models\Fasilitas;
use App\Models\Perbaikan;
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
        $random = fake()->randomElement(['Meja', 'PC', 'Papan Tulis', 'Kursi', 'Pintu', 'Proyektor']);
        return [
            'nama_fasilitas' => $random,
            'kode_fasilitas' => substr($random, 0, 2). fake()->numerify('##'),
            'deskripsi' => fake()->sentence(5),
            'id_kategori' => fake()->randomElement([1, 2, 3]),
            'kondisi' => Kondisi::BAIK,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Fasilitas $fasilitas) {
            Perbaikan::factory()->create(['id_fasilitas' => $fasilitas->id_fasilitas]);
        });
    }
}
