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
                // for ($i = 0; $i < fake()->numberBetween(2, 7); $i++) {
                //     Aduan::create([
                //         'id_periode' => $f->periode->id_periode,
                //         'id_fasilitas' => $f->id_fasilitas,
                //         'tanggal_aduan' => fake()->dateTimeBetween('2025-05-01', '2025-05-31'),
                //         'status' => Status::MENUNGGU_DIPROSES,
                //         'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                //             $query->where('nama_role', '!=', ['teknisi', 'sarpras', 'admin']);
                //         })->inRandomOrder()->first()->id_user,
                //         'deskripsi' => fake()->paragraph(2),
                //         'bukti_foto' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
                //     ]);
                // }
                // Ambil daftar user yang memenuhi kriteria (bukan teknisi, sarpras, atau admin)
                $users = User::query()
                    ->whereHas('role', function ($query) {
                        $query->whereNotIn('nama_role', ['teknisi', 'sarpras', 'admin']);
                    })
                    ->pluck('id_user') // Ambil hanya kolom id_user
                    ->shuffle() // Acak urutan
                    ->toArray(); // Konversi ke array

                // Inisialisasi array untuk menyimpan ID yang sudah digunakan
                $usedUserIds = [];

                // Loop sebanyak jumlah yang diinginkan
                for ($i = 0; $i < fake()->numberBetween(2, 7); $i++) {
                    // Pastikan masih ada user yang belum digunakan
                    if (empty($users)) {
                        break; // Hentikan loop jika tidak ada user lagi
                    }

                    // Ambil ID user pertama yang belum digunakan
                    $userId = array_shift($users); // Ambil dan hapus elemen pertama dari array

                    // Buat aduan dengan user yang dipilih
                    $aduan = Aduan::create([
                        'id_periode' => 2,
                        'id_fasilitas' => $f->id_fasilitas,
                        'tanggal_aduan' => fake()->dateTimeBetween('2025-05-01', '2025-05-31'),
                        'status' => Status::MENUNGGU_DIPROSES,
                        'id_user_pelapor' => $userId,
                        'deskripsi' => fake()->paragraph(2),
                        'bukti_foto' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
                    ]);

                    // Tandai ID user sebagai sudah digunakan
                    $usedUserIds[] = $userId;
                }
            }
            foreach ($f->inspeksi as $inspeksi) {
                // $periodeAduan = $inspeksi->periode;
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
                // Ambil daftar user yang memenuhi kriteria (bukan teknisi, sarpras, atau admin)
                $users = User::query()
                    ->whereHas('role', function ($query) {
                        $query->whereNotIn('nama_role', ['teknisi', 'sarpras', 'admin']);
                    })
                    ->pluck('id_user') // Ambil hanya kolom id_user
                    ->shuffle() // Acak urutan
                    ->toArray(); // Konversi ke array

                // Inisialisasi array untuk menyimpan ID yang sudah digunakan
                $usedUserIds = [];

                // Loop sebanyak jumlah yang diinginkan
                for ($i = 0; $i < fake()->numberBetween(2, 7); $i++) {
                    // Pastikan masih ada user yang belum digunakan
                    if (empty($users)) {
                        break; // Hentikan loop jika tidak ada user lagi
                    }

                    // Ambil ID user pertama yang belum digunakan
                    $userId = array_shift($users); // Ambil dan hapus elemen pertama dari array

                    // Buat aduan dengan user yang dipilih
                    $aduan = Aduan::create([
                        'id_periode' => 2,
                        'id_fasilitas' => $f->id_fasilitas,
                        'tanggal_aduan' => $tanggalAduan,
                        'status' => $status,
                        'id_user_pelapor' => $userId,
                        'deskripsi' => fake()->paragraph(2),
                        'bukti_foto' => fake()->imageUrl(640, 480, 'business', true, 'Aduan', true),
                    ]);

                    // Tandai ID user sebagai sudah digunakan
                    $usedUserIds[] = $userId;
                }
            }
        }
    }
}
