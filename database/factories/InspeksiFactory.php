<?php

namespace Database\Factories;

use App\Models\Biaya;
use App\Models\Fasilitas;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inspeksi>
 */
class InspeksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teknisi = User::query()->whereHas('role', function ($query) {
            $query->where('nama_role', 'teknisi');
        })->inRandomOrder()->first();

        $sarpras = User::query()
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'sarpras');
            })->inRandomOrder()->first();

        $fasilitas = Fasilitas::all()->random();
        $periode = Periode::find(2);

        $taggalMulai = fake()->dateTimeBetween($periode->tanggal_mulai, $periode->tanggal_selesai);

        return [
            'id_user_teknisi' => $teknisi->id_user,
            'id_user_sarpras' => $sarpras->id_user,
            'id_fasilitas' => $fasilitas->id_fasilitas,

            'tingkat_kerusakan' => fake()->randomElement(['RINGAN', 'SEDANG', 'PARAH']),
            'deskripsi' => fake()->paragraph(2),

            'id_periode' => $periode->id_periode,
            'tanggal_mulai' => $taggalMulai,
            'tanggal_selesai' => fake()->dateTimeBetween($taggalMulai, $periode->tanggal_selesai),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function ($inspeksi) {
            if (fake()->boolean()) {
                # code...

                // Additional logic after creating an Inspeksi instance can be added here
                $nextPeriode = Periode::where('id_periode', '>', $inspeksi->id_periode)
                    ->orderBy('id_periode')
                    ->first();
                $tanggalMulai = fake()->dateTimeBetween($nextPeriode->tanggal_mulai, $nextPeriode->tanggal_selesai);
                Perbaikan::factory()->create([
                    'id_inspeksi' => $inspeksi->id_inspeksi,
                    'id_periode' => $inspeksi->id_periode + 1,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => fake()->dateTimeBetween($tanggalMulai, $nextPeriode->tanggal_selesai),
                ]);

                Biaya::factory()->create([
                    'id_inspeksi' => $inspeksi->id_inspeksi,
                ]);
            }
        });
    }
}
