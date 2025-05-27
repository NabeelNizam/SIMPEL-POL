<?php

namespace Database\Factories;

use App\Models\Lantai;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lantai>
 */
class LantaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lantai' => fake()->unique()->numerify('##'),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Lantai $lantai) {
            $ruangan = Ruangan::factory()->create(['id_lantai' => $lantai->id_lantai]);
        });
    }
}
