<?php

namespace Database\Factories;

use App\Models\Gedung;
use App\Models\Lantai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gedung>
 */
class GedungFactory extends Factory
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
            'nama_gedung' => $random,
            'kode_gedung' => substr($random, 0, 2). fake()->numerify('##'),
            'id_jurusan' => 1,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Gedung $gedung) {
            $lantai = Lantai::factory()->create(['id_gedung' => $gedung->id_gedung]);
        });
    }
}
