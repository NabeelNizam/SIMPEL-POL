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
                    $status = Status::MENUNGGU_DIPROSES;
                }
                $aduan = Aduan::create([
                    'id_periode' => $periodeAduan->id_periode,
                    'id_fasilitas' => $f->id_fasilitas,
                    'tanggal_aduan' => $tanggalAduan,
                    'status' => $status,
                    'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                        $query->where('nama_role', '!=', 'admin|teknisi');
                    })->inRandomOrder()->first()->id_user,
                    'deskripsi' => fake()->paragraph(2),
                    'bukti_foto' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
                ]);
            }
        }
    }
}
