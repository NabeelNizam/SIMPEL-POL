<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UmpanBalik>
 */
class UmpanBalikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'komentar' => fake()->sentence(5),
            'rating' => fake()->randomElement([1, 2, 3, 4, 5]),
        ];
    }
}
