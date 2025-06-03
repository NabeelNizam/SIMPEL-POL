<?php

namespace Database\Factories;

use App\Models\Aduan;
use App\Models\Perbaikan;
use App\Models\Prioritas;
use App\Models\UmpanBalik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aduan>
 */
class AduanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $numbers = [1, 5, 6];
        return [
            'tanggal_aduan' => fake()->date(),
            'deskripsi' => fake()->sentence(5),
            'status' => 'MENUNGGU_DIPROSES',
            'bukti_foto' => 'bukti_foto.jpg',
            'id_fasilitas' => rand(1, 3),
            'id_user_pelapor' => $numbers[array_rand($numbers)],
            'id_periode' => 1,
            'id_perbaikan' => 1
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Aduan $aduan) {
            UmpanBalik::factory()->create(['id_aduan' => $aduan->id_aduan]);
            // Prioritas::factory()->create(['id_aduan' => $aduan->id_aduan]);
        });
    }
}
