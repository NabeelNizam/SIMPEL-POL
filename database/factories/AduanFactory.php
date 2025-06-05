<?php

namespace Database\Factories;

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
        // Mengambil periode acak dari database supaya tanggal aduan tidak di luar periode yang ada
        $periode = Periode::all()->random();

        // Menggunakan tanggal acak antara tanggal mulai dan tanggal selesai periode yang diambil
        $date = $this->faker->dateTimeBetween($periode->tanggal_mulai, $periode->tanggal_selesai)->format('Y-m-d');

        $status = 'MENUNGGU_DIPROSES';
        // Mengambil perbaikan acak dari database, dan kadang-kadang mengisi id_perbaikan dengan null
        $isDiizinkanDiinspeksi = fake()->boolean();
        if ($isDiizinkanDiinspeksi) {
            $perbaikan = Perbaikan::get('id_perbaikan')->random();
            // $perbaikan = Fasilitas::find();
            $status = 'SEDANG_INSPEKSI';

            $isDiisinkanDiperbaiki = fake()->boolean();
            if ($isDiisinkanDiperbaiki) {
                $status= 'SEDANG_DIPERBAIKI';

                $isSelesai = fake()->boolean();
                if ($isSelesai) {
                    $status = 'SELESAI';
                }
            }
        }

        // Mengambil user pelapor acak dari database, dengan id_user yang sesuai
        $pelapor = User::query()
            ->whereHas('role', function ($query) {
                $query->whereIn('nama_role', ['MAHASISWA', 'PEGAWAI']);
            })
            ->with('role') // optional: only if you want to eager-load the relation
            ->inRandomOrder()
            ->first(['id_user']);

        $numbers = [1, 5, 6];
        return [
            'id_user_pelapor' => $pelapor->id_user,
            'deskripsi' => fake()->sentence(5),
            'tanggal_aduan' => $date,
            'bukti_foto' => 'bukti_foto.jpg',
            'id_periode' => $periode->id_periode,
            // perbaikan muncul jika aduan diizinkan untuk diinspeksi
            'status' => $status,
            'id_perbaikan' => $perbaikan->id_perbaikan ?? null,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Aduan $aduan) {
            if ($aduan->status === 'SELESAI')
            {
                UmpanBalik::factory()->create(['id_aduan' => $aduan->id_aduan]);
            }
            // Prioritas::factory()->create(['id_aduan' => $aduan->id_aduan]);
        });
    }
}
