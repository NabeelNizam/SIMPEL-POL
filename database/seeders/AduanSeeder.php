<?php

namespace Database\Seeders;

use App\Http\Enums\Status;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = Fasilitas::all();
        foreach ($fasilitas as $f) {
            if ($f->inspeksi->isEmpty()) {
                for ($i = 0; $i < fake()->numberBetween(2, 7); $i++) {
                    Aduan::create([
                        'id_periode' => $f->periode->id_periode,
                        'id_fasilitas' => $f->id_fasilitas,
                        'tanggal_aduan' => fake()->dateTimeBetween('2025-05-01', '2025-05-31'),
                        'status' => Status::MENUNGGU_DIPROSES,
                        'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                            $query->where('nama_role', '!=', ['teknisi', 'sarpras', 'admin']);
                        })->inRandomOrder()->first()->id_user,
                        'deskripsi' => fake()->paragraph(2),
                        'bukti_foto' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
                    ]);
                }
            }
            foreach ($f->inspeksi as $inspeksi) {
                $periodeAduan = $inspeksi->periode;
                $tanggalAduan = Carbon::parse($inspeksi->tanggal_mulai)->subDay()->toDateString();

                // Tentukan status berdasarkan kondisi inspeksi
                if (isset($inspeksi->perbaikan->tanggal_selesai)) {
                    $status = Status::SELESAI;
                } else
                if (isset($inspeksi->perbaikan)) {
                    $status = Status::SEDANG_DIPERBAIKI;
                } else
                if (isset($inspeksi)) {
                    $status = Status::SEDANG_INSPEKSI;
                } else {
                    throw new \Exception('Status aduan tidak ditemukan');
                }
                for ($i = 0; $i < fake()->numberBetween(2, 7); $i++) {
                    $aduan = Aduan::create([
                        'id_periode' => $periodeAduan->id_periode,
                        'id_fasilitas' => $f->id_fasilitas,
                        'tanggal_aduan' => $tanggalAduan,
                        'status' => $status,
                        'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                            $query->where('nama_role', '!=', ['teknisi', 'sarpras', 'admin']);
                        })->inRandomOrder()->first()->id_user,
                        'deskripsi' => fake()->paragraph(2),
                        'bukti_foto' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
                    ]);
                }
            }
        }
    }
}
