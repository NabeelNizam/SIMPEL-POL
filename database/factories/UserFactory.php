<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'username' => fake()->unique()->userName(),
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => 1,
            'password' => '$2a$12$aZ6CFpNbp6fQKXiG0evCLuqKRwnMnPiWCg1nu3j2/tVZXu5MLIVEe', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $mahasiswa = Mahasiswa::factory()->create();
            $user->identifier()->associate($mahasiswa); // Set the user_id in the related model;
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
