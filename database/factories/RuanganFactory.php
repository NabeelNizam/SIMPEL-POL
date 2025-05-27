<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruangan>
 */
class RuanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $random = fake()->lastName();
        return [
            'nama_ruangan' => $random,
            'kode_ruangan' => substr($random, 0, 2). fake()->numerify('##'),

        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Ruangan $ruangan) {
            $fasilitas = Fasilitas::factory()->create(['id_ruangan' => $ruangan->id_ruangan]);
        });
    }
}
