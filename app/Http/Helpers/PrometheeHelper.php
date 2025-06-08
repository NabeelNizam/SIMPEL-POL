<?php

namespace App\Http\Helpers;

use App\Http\Enums\JenisKriteria;
use App\Http\Enums\TingkatKerusakan;
use App\Http\Enums\Urgensi;
use App\Models\Biaya;
use Illuminate\Support\Facades\Log;

class PrometheeHelper
{
    public static function processPromethee($inspeksi, $kriteria)
    {
        // Langkah 1: Cek data awal
        if (empty($inspeksi)) {
            Log::info('PrometheeHelper: Data inspeksi kosong');
            return [];
        }

        $idInspeksiList = array_column($inspeksi, 'id_inspeksi');
        Log::info('PrometheeHelper Langkah 1 - Data Awal', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => $idInspeksiList,
        ]);

        // Periksa duplikasi awal
        $idCounts = array_count_values($idInspeksiList);
        foreach ($idCounts as $id => $count) {
            if ($count > 1) {
                Log::warning("PrometheeHelper: Duplikasi id_inspeksi ditemukan di data awal: id_inspeksi $id muncul $count kali", $inspeksi);
            }
        }

        // Langkah 2: Hapus duplikasi berdasarkan id_inspeksi
        $uniqueInspeksi = [];
        $seenIds = [];
        foreach ($inspeksi as $item) {
            if (!in_array($item['id_inspeksi'], $seenIds)) {
                $uniqueInspeksi[] = $item;
                $seenIds[] = $item['id_inspeksi'];
            } else {
                Log::warning("PrometheeHelper: Duplikasi id_inspeksi ditemukan saat deduplikasi: id_inspeksi {$item['id_inspeksi']}", $item);
            }
        }
        $inspeksi = array_values($uniqueInspeksi);

        Log::info('PrometheeHelper Langkah 2 - Setelah Deduplikasi', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
        ]);

        // Langkah 3: Konversi data ENUM dan ambil biaya
        foreach ($inspeksi as &$item) {
            // Konversi urgensi ke nilai numerik
            $urgensi = $item['fasilitas']['urgensi'] ?? 'BIASA';
            $item['urgensi_fasilitas'] = match ($urgensi) {
                Urgensi::DARURAT->value => 3,
                Urgensi::PENTING->value => 2,
                Urgensi::BIASA->value => 1,
                default => 1,
            };

            // Simpan tingkat_kerusakan asli dan konversi ke numerik
            $tingkatKerusakan = $item['tingkat_kerusakan'] ?? 'RINGAN';
            $item['tingkat_kerusakan_asli'] = $tingkatKerusakan;
            $item['tingkat_kerusakan'] = TingkatKerusakan::from($tingkatKerusakan)->toNumeric();

            // Ambil biaya
            $item['biaya'] = Biaya::where('id_inspeksi', $item['id_inspeksi'])->sum('besaran') ?? 0;

            // Konversi waktu_selesai ke timestamp
            $item['waktu_selesai'] = strtotime($item['tanggal_selesai'] ?? now());

            // Validasi user_count
            if (!isset($item['user_count']) || !is_numeric($item['user_count'])) {
                Log::warning("PrometheeHelper: user_count tidak valid untuk id_inspeksi {$item['id_inspeksi']}", $item);
                $item['user_count'] = 0;
            }
        }
        unset($item);

        Log::info('PrometheeHelper Langkah 3 - Setelah Konversi Data', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'data_kriteria' => array_map(function ($item) {
                return [
                    'id_inspeksi' => $item['id_inspeksi'],
                    'user_count' => $item['user_count'],
                    'urgensi_fasilitas' => $item['urgensi_fasilitas'],
                    'biaya' => $item['biaya'],
                    'tingkat_kerusakan' => $item['tingkat_kerusakan'],
                    'tingkat_kerusakan_asli' => $item['tingkat_kerusakan_asli'],
                    'waktu_selesai' => $item['waktu_selesai'],
                ];
            }, $inspeksi),
        ]);

        // Langkah 4: Hitung ranking untuk waktu_selesai
        $waktuSelesai = array_column($inspeksi, 'waktu_selesai');
        if (!empty($waktuSelesai)) {
            arsort($waktuSelesai);
            $waktuRanking = [];
            $rank = 1;
            foreach ($waktuSelesai as $index => $value) {
                $waktuRanking[$index] = $rank++;
            }

            foreach ($inspeksi as $index => &$item) {
                $item['waktu'] = $waktuRanking[$index] ?? 1;
            }
            unset($item);
        } else {
            foreach ($inspeksi as &$item) {
                $item['waktu'] = 1;
            }
            unset($item);
        }

        Log::info('PrometheeHelper Langkah 4 - Setelah Ranking Waktu Selesai', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'waktu_ranking' => array_map(function ($item) {
                return [
                    'id_inspeksi' => $item['id_inspeksi'],
                    'waktu' => $item['waktu'],
                ];
            }, $inspeksi),
        ]);

        // Langkah 5: Normalisasi data
        $normalized = [];
        $inspeksi = array_values($inspeksi);
        foreach ($kriteria as $k => $krit) {
            $values = array_column($inspeksi, $k);
            $values = array_filter($values, fn($v) => $v !== null);
            if (empty($values)) {
                Log::warning("PrometheeHelper: Kriteria $k tidak memiliki nilai valid", $values);
                foreach ($inspeksi as $i => $item) {
                    $normalized[$i][$k] = 0;
                }
                continue;
            }

            $min = min($values);
            $max = max($values);
            $range = $max - $min ?: 1;

            foreach ($inspeksi as $i => $item) {
                $value = $item[$k] ?? 0;
                if ($krit['jenis_kriteria'] == JenisKriteria::BENEFIT->value) {
                    $normalized[$i][$k] = ($value - $min) / $range;
                } else {
                    $normalized[$i][$k] = ($max - $value) / $range;
                }
            }
        }

        Log::info('PrometheeHelper Langkah 5 - Setelah Normalisasi', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'normalized_data' => array_map(function ($i) use ($normalized, $kriteria, $inspeksi) {
                $data = ['id_inspeksi' => $inspeksi[$i]['id_inspeksi']];
                foreach ($kriteria as $k => $krit) {
                    $data[$k] = $normalized[$i][$k] ?? 0;
                }
                return $data;
            }, array_keys($inspeksi)),
        ]);

        // Langkah 6: Hitung preference function
        $preference = [];
        $n = count($inspeksi);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i != $j) {
                    $preference[$i][$j] = 0;
                    foreach ($kriteria as $k => $krit) {
                        $diff = ($normalized[$i][$k] ?? 0) - ($normalized[$j][$k] ?? 0);
                        $p = $diff > 0 ? $diff : 0;
                        $preference[$i][$j] += $krit['bobot'] * $p;
                    }
                }
            }
        }

        $preferenceSample = [];
        foreach (array_keys($inspeksi) as $i) {
            $preferenceSample[] = [
                'id_inspeksi' => $inspeksi[$i]['id_inspeksi'],
                'preference' => $preference[$i] ?? [],
            ];
        }
        Log::info('PrometheeHelper Langkah 6 - Setelah Preference Function', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'preference_sample' => $preferenceSample,
        ]);

        // Langkah 7: Hitung leaving flow, entering flow, dan net flow
        $leavingFlow = [];
        $enteringFlow = [];
        $netFlow = [];
        for ($i = 0; $i < $n; $i++) {
            $leavingFlow[$i] = array_sum($preference[$i] ?? []) / ($n - 1 ?: 1);
            $enteringFlow[$i] = 0;
            for ($j = 0; $j < $n; $j++) {
                if ($i != $j) {
                    $enteringFlow[$i] += ($preference[$j][$i] ?? 0) / ($n - 1 ?: 1);
                }
            }
            $netFlow[$i] = $leavingFlow[$i] - $enteringFlow[$i];
        }

        Log::info('PrometheeHelper Langkah 7 - Setelah Flow Calculation', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'flows' => array_map(function ($i) use ($leavingFlow, $enteringFlow, $netFlow, $inspeksi) {
                return [
                    'id_inspeksi' => $inspeksi[$i]['id_inspeksi'],
                    'leaving_flow' => $leavingFlow[$i] ?? 0,
                    'entering_flow' => $enteringFlow[$i] ?? 0,
                    'net_flow' => $netFlow[$i] ?? 0,
                ];
            }, array_keys($inspeksi)),
        ]);

        // Langkah 8: Tambahkan leaving_flow, entering_flow, net_flow (skor), dan ranking
        $seenIds = [];
        arsort($netFlow);
        $rank = 1;
        foreach ($netFlow as $index => $score) {
            if (!in_array($inspeksi[$index]['id_inspeksi'], $seenIds)) {
                $inspeksi[$index]['leaving_flow'] = round($leavingFlow[$index], 4);
                $inspeksi[$index]['entering_flow'] = round($enteringFlow[$index], 4);
                $inspeksi[$index]['skor'] = round($score, 4);
                $inspeksi[$index]['ranking'] = $rank++;
                $seenIds[] = $inspeksi[$index]['id_inspeksi'];
            } else {
                Log::warning("PrometheeHelper: Duplikasi id_inspeksi saat penetapan ranking: id_inspeksi {$inspeksi[$index]['id_inspeksi']}", $inspeksi[$index]);
            }
        }

        Log::info('PrometheeHelper Langkah 8 - Setelah Penambahan Flow dan Ranking', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => array_column($inspeksi, 'id_inspeksi'),
            'flow_ranking' => array_map(function ($item) {
                return [
                    'id_inspeksi' => $item['id_inspeksi'],
                    'leaving_flow' => $item['leaving_flow'] ?? null,
                    'entering_flow' => $item['entering_flow'] ?? null,
                    'skor' => $item['skor'] ?? null,
                    'ranking' => $item['ranking'] ?? null,
                ];
            }, $inspeksi),
        ]);

        // Langkah 9: Urutkan berdasarkan ranking
        usort($inspeksi, function ($a, $b) {
            return ($a['ranking'] ?? PHP_INT_MAX) <=> ($b['ranking'] ?? PHP_INT_MAX);
        });

        // Periksa duplikasi akhir
        $idInspeksiList = array_column($inspeksi, 'id_inspeksi');
        $idCounts = array_count_values($idInspeksiList);
        foreach ($idCounts as $id => $count) {
            if ($count > 1) {
                Log::warning("PrometheeHelper: Duplikasi id_inspeksi ditemukan di hasil akhir: id_inspeksi $id muncul $count kali", $inspeksi);
            }
        }

        Log::info('PrometheeHelper Langkah 9 - Hasil Akhir', [
            'jumlah_inspeksi' => count($inspeksi),
            'id_inspeksi' => $idInspeksiList,
            'hasil' => array_map(function ($item) {
                return [
                    'id_inspeksi' => $item['id_inspeksi'],
                    'leaving_flow' => $item['leaving_flow'] ?? null,
                    'entering_flow' => $item['entering_flow'] ?? null,
                    'skor' => $item['skor'] ?? null,
                    'ranking' => $item['ranking'] ?? null,
                    'waktu' => $item['waktu'] ?? null,
                    'tingkat_kerusakan_asli' => $item['tingkat_kerusakan_asli'] ?? null,
                ];
            }, $inspeksi),
        ]);

        return $inspeksi;
    }
}