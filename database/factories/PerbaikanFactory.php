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
        $periode = Periode::all()->random();
        $date = $this->faker->dateTimeBetween($periode->tanggal_mulai, $periode->tanggal_selesai)->format('Y-m-d');
        $isInspected = fake()->boolean();
        if ($isInspected) {
            $deskripsiPerbaikan = fake()->sentence(5);
            $tingkatKerusakan = fake()->randomElement(['RINGAN', 'SEDANG', 'PARAH']);
            $tanggalInspeksi = $this->faker->dateTimeBetween($date, $periode->tanggal_selesai)->format('Y-m-d');
            $isAccepted = fake()->boolean();
            $isRepaired = fake()->boolean();
            if ( $isRepaired && $isAccepted) {
                $dateSelesai = $this->faker->dateTimeBetween($tanggalInspeksi, $periode->tanggal_selesai)->format('Y-m-d');
            }
        }
        return [
            'id_user_teknisi' => 3,
            'id_user_sarpras' => 4,
            'tanggal_mulai' => $date,
            'id_periode' => $periode->id_periode,

            // Jika sudah diinspeksi, maka deskripsi dan tingkat kerusakan akan diisi
            'deskripsi_perbaikan' => $deskripsiPerbaikan ?? null,
            'tingkat_kerusakan' => $tingkatKerusakan ?? null,
            'tanggal_inspeksi' =>  $tanggalInspeksi ?? null,

            // jika sudah diperbaiki, maka is_teknisi_selesai akan diisi
            'is_teknisi_selesai_perbaikan' => $isRepaired ?? false,

            // jika perbaikan sudah diterima sarpras, maka tanggal_selesai akan diisi
            'tanggal_selesai' => $dateSelesai ?? null,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Perbaikan $perbaikan) {
            if ($perbaikan->tanggal_inspeksi) {
                Biaya::factory(3)->create(['id_perbaikan' => $perbaikan->id_perbaikan]);
                Prioritas::factory()->create(['id_perbaikan' => $perbaikan->id_perbaikan]);
            }

        });
    }
}
