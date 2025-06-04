<?php

namespace Database\Seeders;

use App\Http\Enums\Kondisi;
use App\Http\Enums\Status;
use App\Http\Enums\Urgensi;
use App\Models\Aduan;
use App\Models\Fasilitas;
use App\Models\Perbaikan;
use App\Models\Periode;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $random = fake()->randomElement(['Meja', 'PC', 'Papan Tulis', 'Kursi', 'Pintu', 'Proyektor']);
            $fasilitas = Fasilitas::create([
                'nama_fasilitas' => $random,
                'kode_fasilitas' => substr($random, 0, 2) . fake()->unique()->numerify('##'),
                'deskripsi' => fake()->sentence(5),
                'id_kategori' => fake()->randomElement([1, 2, 3]),
                'kondisi' => Kondisi::LAYAK,
                'urgensi' => Urgensi::BIASA,
                'foto_fasilitas' => fake()->image(),
                'id_periode' => 1,
                'id_ruangan' => Ruangan::all()->random()->id_ruangan,
            ]);

            $state = Status::from(fake()->randomElement(['MENUNGGU_DIPROSES', 'SEDANG_INSPEKSI', 'SEDANG_DIPERBAIKI', 'SELESAI']));
            $periode = Periode::all()->random();
            $tanggal_aduan = fake()->dateTimeBetween($periode->tanggal_mulai, $periode->tanggal_selesai);
            $tanggal_mulai_perbaikan = fake()->dateTimeBetween($tanggal_aduan, $periode->tanggal_selesai);
            $tanggal_inspeksi = fake()->dateTimeBetween($tanggal_mulai_perbaikan, $periode->tanggal_selesai);

            // $state= Status::SEDANG_DIPERBAIKI;
            // pertama kali submit
            if ($state == Status::MENUNGGU_DIPROSES) {

                $aduan = Aduan::create([
                    'status' => Status::MENUNGGU_DIPROSES,
                    'tanggal_aduan' => $tanggal_aduan,
                    'id_fasilitas' => $fasilitas->id_fasilitas,
                    'id_periode' => $periode->id_periode,
                    'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                        $query->whereIn('nama_role', ['MAHASISWA', 'DOSEN', 'TENDIK']);
                    })->inRandomOrder()->first()->id_user,
                    'deskripsi' => fake()->sentence(5),
                    'bukti_foto' => fake()->image(),
                ]);
            } else
                // diizinkan sarpras untuk diinspeksi
                if ($state == Status::SEDANG_INSPEKSI) {
                    $perbaikan = Perbaikan::create([
                        'id_fasilitas' => $fasilitas->id_fasilitas,
                        'id_periode' => $periode->id_periode,
                        'id_user_sarpras' =>  User::query()->whereHas('role', function ($query) {
                            $query->whereIn('nama_role', ['SARPRAS']);
                        })->inRandomOrder()->first()->id_user,
                        'id_user_teknisi' =>  User::query()->whereHas('role', function ($query) {
                            $query->whereIn('nama_role', ['TEKNISI']);
                        })->inRandomOrder()->first()->id_user,
                        'tanggal_mulai' => $tanggal_mulai_perbaikan,
                    ]);

                    $aduan = Aduan::create([
                        'status' => Status::SEDANG_INSPEKSI,
                        'tanggal_aduan' => $tanggal_aduan,
                        'id_fasilitas' => $fasilitas->id_fasilitas,
                        'id_periode' => $periode->id_periode,
                        'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                            $query->whereIn('nama_role', ['MAHASISWA', 'DOSEN', 'TENDIK']);
                        })->inRandomOrder()->first()->id_user,
                        'deskripsi' => fake()->sentence(5),
                        'bukti_foto' => fake()->image(),
                        'id_perbaikan' => $perbaikan->id_perbaikan,
                    ]);
                } else
                    // jika sudah diinspeksi, maka sarpras akan mengizinkan perbaikan
                    if ($state == Status::SEDANG_DIPERBAIKI) {
                        $teknisi_selesai_perbaikan = fake()->boolean();

                        // jika teknisi sudah selesai perbaikan
                        if ($teknisi_selesai_perbaikan) {
                            $perbaikan = Perbaikan::create([
                                'id_fasilitas' => $fasilitas->id_fasilitas,
                                'id_periode' => $periode->id_periode,
                                'id_user_sarpras' =>  User::query()->whereHas('role', function ($query) {
                                    $query->whereIn('nama_role', ['SARPRAS']);
                                })->inRandomOrder()->first()->id_user,
                                'id_user_teknisi' =>  User::query()->whereHas('role', function ($query) {
                                    $query->whereIn('nama_role', ['TEKNISI']);
                                })->inRandomOrder()->first()->id_user,
                                'tanggal_mulai' => $tanggal_mulai_perbaikan,
                                'deskripsi_perbaikan' => fake()->sentence(5),
                                'tingkat_kerusakan' => fake()->randomElement(['RINGAN', 'SEDANG', 'PARAH']),
                                'tanggal_inspeksi' => $tanggal_inspeksi,
                                'is_teknisi_selesai_perbaikan' => true,
                            ]);

                            $aduan = Aduan::create([
                                'status' => Status::SEDANG_DIPERBAIKI,
                                'tanggal_aduan' => $tanggal_aduan,
                                'id_fasilitas' => $fasilitas->id_fasilitas,
                                'id_periode' => $periode->id_periode,
                                'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                                    $query->whereIn('nama_role', ['MAHASISWA', 'DOSEN', 'TENDIK']);
                                })->inRandomOrder()->first()->id_user,
                                'deskripsi' => fake()->sentence(5),
                                'bukti_foto' => fake()->image(),
                                'id_perbaikan' => $perbaikan->id_perbaikan,
                            ]);
                            // jika teknisi belum selesai perbaikan
                        } else {
                            $perbaikan = Perbaikan::create([
                                'id_fasilitas' => $fasilitas->id_fasilitas,
                                'id_periode' => $periode->id_periode,
                                'id_user_sarpras' =>  User::query()->whereHas('role', function ($query) {
                                    $query->whereIn('nama_role', ['SARPRAS']);
                                })->inRandomOrder()->first()->id_user,
                                'id_user_teknisi' =>  User::query()->whereHas('role', function ($query) {
                                    $query->whereIn('nama_role', ['TEKNISI']);
                                })->inRandomOrder()->first()->id_user,
                                'tanggal_mulai' => fake()->dateTimeBetween($tanggal_aduan, $periode->tanggal_selesai),
                                'deskripsi_perbaikan' => fake()->sentence(5),
                                'tingkat_kerusakan' => fake()->randomElement(['RINGAN', 'SEDANG', 'PARAH']),
                                'tanggal_inspeksi' => $tanggal_inspeksi,
                            ]);

                            $aduan = Aduan::create([
                                'status' => Status::SEDANG_DIPERBAIKI,
                                'tanggal_aduan' => $tanggal_aduan,
                                'id_fasilitas' => $fasilitas->id_fasilitas,
                                'id_periode' => $periode->id_periode,
                                'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                                    $query->whereIn('nama_role', ['MAHASISWA', 'DOSEN', 'TENDIK']);
                                })->inRandomOrder()->first()->id_user,
                                'deskripsi' => fake()->sentence(5),
                                'bukti_foto' => fake()->image(),
                                'id_perbaikan' => $perbaikan->id_perbaikan,
                            ]);
                        }
                    } else {
                        $perbaikan = Perbaikan::create([
                            'id_fasilitas' => $fasilitas->id_fasilitas,
                            'id_periode' => $periode->id_periode,
                            'id_user_sarpras' =>  User::query()->whereHas('role', function ($query) {
                                $query->whereIn('nama_role', ['SARPRAS']);
                            })->inRandomOrder()->first()->id_user,
                            'id_user_teknisi' =>  User::query()->whereHas('role', function ($query) {
                                $query->whereIn('nama_role', ['TEKNISI']);
                            })->inRandomOrder()->first()->id_user,
                            'tanggal_mulai' => fake()->dateTimeBetween($tanggal_aduan, $periode->tanggal_selesai),
                            'deskripsi_perbaikan' => fake()->sentence(5),
                            'tingkat_kerusakan' => fake()->randomElement(['RINGAN', 'SEDANG', 'PARAH']),
                            'tanggal_inspeksi' => $tanggal_inspeksi,
                            'is_teknisi_selesai_perbaikan' => true,
                            'tanggal_selesai' => fake()->dateTimeBetween($tanggal_inspeksi, $periode->tanggal_selesai),
                        ]);

                        $aduan = Aduan::create([
                            'status' => Status::SELESAI,
                            'tanggal_aduan' => $tanggal_aduan,
                            'id_fasilitas' => $fasilitas->id_fasilitas,
                            'id_periode' => $periode->id_periode,
                            'id_user_pelapor' => User::query()->whereHas('role', function ($query) {
                                $query->whereIn('nama_role', ['MAHASISWA', 'DOSEN', 'TENDIK']);
                            })->inRandomOrder()->first()->id_user,
                            'deskripsi' => fake()->sentence(5),
                            'bukti_foto' => fake()->image(),
                            'id_perbaikan' => $perbaikan->id_perbaikan,
                        ]);
                    }
        }
    }
}
