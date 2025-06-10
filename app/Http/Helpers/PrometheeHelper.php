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

        // Langkah 2: Konversi data ENUM dan ambil biaya
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

            // Validasi user_count
            if (!isset($item['user_count']) || !is_numeric($item['user_count'])) {
                Log::warning("PrometheeHelper: user_count tidak valid untuk id_inspeksi {$item['id_inspeksi']}", $item);
                $item['user_count'] = 0;
            }

            // Validasi laporan_berulang
            if (!isset($item['laporan_berulang']) || !is_numeric($item['laporan_berulang'])) {
                Log::warning("PrometheeHelper: laporan_berulang tidak valid untuk id_inspeksi {$item['id_inspeksi']}", $item);
                $item['laporan_berulang'] = 0;
            }

            // Validasi bobot_pelapor
            if (!isset($item['bobot_pelapor']) || !is_numeric($item['bobot_pelapor'])) {
                Log::warning("PrometheeHelper: bobot_pelapor tidak valid untuk id_inspeksi {$item['id_inspeksi']}", $item);
                $item['bobot_pelapor'] = 0;
            }
        }
        unset($item);

        // Langkah 3: Normalisasi data
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

        // Langkah 4: Hitung preference function
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

        // Langkah 5: Hitung leaving flow, entering flow, dan net flow
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

        // Langkah 6: Tambahkan leaving_flow, entering_flow, net_flow (skor), dan ranking
        arsort($netFlow);
        $rank = 1;
        foreach ($netFlow as $index => $score) {
            $inspeksi[$index]['leaving_flow'] = round($leavingFlow[$index], 4);
            $inspeksi[$index]['entering_flow'] = round($enteringFlow[$index], 4);
            $inspeksi[$index]['skor'] = round($score, 4);
            $inspeksi[$index]['ranking'] = $rank++;
        }

        // Langkah 7: Urutkan berdasarkan ranking
        usort($inspeksi, function ($a, $b) {
            return ($a['ranking'] ?? PHP_INT_MAX) <=> ($b['ranking'] ?? PHP_INT_MAX);
        });

        return $inspeksi;
    }
}