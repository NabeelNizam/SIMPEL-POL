<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Nette\Utils\Random;
use Random\Randomizer;

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
    private $RAND_ELEMENTS = [2, 5, 6];
    public function definition(): array
    {
        $role = $this->faker->randomElement($this->RAND_ELEMENTS);
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'username' => fake()->unique()->userName(),
            'no_hp' => fake()->phoneNumber(),
            'foto_profil' => fake()->image(),
            'id_role' => $role,
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'id_jurusan' => 1
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if ($user->id_role == 2) {
                $mahasiswa = Mahasiswa::factory()->create([
                    'id_user' => $user->id_user]);

            } else {
                $pegawai = Pegawai::factory()->create(
                    ['id_user' => $user->id_user]
                );

            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
