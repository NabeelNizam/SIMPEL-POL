<?php

namespace Database\Factories;

use App\Models\Biaya;
use App\Models\Perbaikan;
use App\Models\Prioritas;
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
        return [
            'deskripsi_perbaikan' => fake()->sentence(5),
            'tingkat_kerusakan' => 'RINGAN',
            'id_user_teknisi' => 3,
            'id_user_sarpras' => 4,
            'tanggal_mulai' => fake()->date(),
            'tanggal_selesai' => '2027-01-01',
            'id_periode' => 1,
            'is_teknisi_selesai' => fake()->boolean(),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Perbaikan $perbaikan) {
            Biaya::factory(3)->create(['id_perbaikan' => $perbaikan->id_perbaikan]);
            Prioritas::factory()->create(['id_perbaikan' => $perbaikan->id_perbaikan]);
        });
    }
}
