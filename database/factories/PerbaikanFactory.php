<?php

namespace Database\Factories;

use App\Models\Biaya;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\Prioritas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perbaikan>
 */
class PerbaikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return[
            'deskripsi' => fake()->paragraph(2),
        ];
    }
}
