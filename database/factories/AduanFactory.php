<?php

namespace Database\Factories;

use App\Http\Enums\Status;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\Prioritas;
use App\Models\UmpanBalik;
use App\Models\User;
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
        return [
            'id_fasilitas' => Fasilitas::all()->random()->id_fasilitas,
            'id_user_pelapor' => User::all()->random()->id_user,
            'id_periode' => Periode::find(2)->id_periode,
            'judul' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(2),
            'foto_aduan' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
            'status' => Status::SELESAI,
        ];
    }
    public function configure() {}
}
