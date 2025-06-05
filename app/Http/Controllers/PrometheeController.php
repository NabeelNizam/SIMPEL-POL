<?php

namespace App\Http\Controllers;

use App\Http\Enums\Urgensi;
use App\Http\Helpers\AlternativeDTO;
use App\Http\Helpers\PrometheeCalculator;
use App\Models\Aduan;
use App\Models\Kriteria;
use App\Models\Role;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrometheeController extends Controller
{
    public function calculatePromethee()
    {

        // role_id = 1 (mahasiswa)
        // role_id = 5 (dosen)
        // role_id = 6 (tendik)

        $role_id = 1; // default mahasiswa

        // Step 1: Fetch criteria weights and normalize
        $kriteria = Kriteria::whereIn('nama_kriteria', ['User Count', 'Urgensi'])->get();
        $totalBobot = $kriteria->sum('bobot') ?: 1; // Avoid division by zero
        $weights = [];
        $criteriaMap = [
            'User Count' => 'user_count',
            'Urgensi' => 'urgensi',
        ];
        foreach ($kriteria as $k) {
            $mappedKey = $criteriaMap[$k->nama_kriteria] ?? $k->nama_kriteria;
            $weights[$mappedKey] = $k->bobot / $totalBobot;
        }

        // Step 2: Fetch the specified role
        $role = Role::findOrFail($role_id);
        $alternativesPromethee = []; // For PROMETHEE calculation
        $alternativesZeroCount = []; // For user_count = 0

        // Step 3: Fetch facilities that have ever been reported in aduan
        $facilities = Aduan::join('fasilitas', 'aduan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
            ->select('fasilitas.id_fasilitas', 'fasilitas.nama_fasilitas')
            ->distinct()
            ->get();

        foreach ($facilities as $facility) {
            // User Count: Count number of reports for this facility by this role
            $userCount = Aduan::whereHas('pelapor', function ($query) use ($role) {
                $query->where('id_role', $role->id_role);
            })
                ->where('id_fasilitas', $facility->id_fasilitas)
                ->count();

            // Urgensi: Get raw urgency value from fasilitas
            $fasilitas = Fasilitas::where('id_fasilitas', $facility->id_fasilitas)->first();
            $rawUrgensi = $fasilitas ? $fasilitas->urgensi : null;
            Log::info("Raw urgensi for facility {$facility->nama_fasilitas}:", ['raw_urgensi' => $rawUrgensi]);

            // Convert enum value to numeric
            $urgensi = 1; // Default to BIASA
            if ($rawUrgensi instanceof Urgensi) {
                $urgensi = match ($rawUrgensi) {
                    Urgensi::DARURAT => 3,
                    Urgensi::PENTING => 2,
                    Urgensi::BIASA => 1,
                    default => 1,
                };
            } elseif (is_string($rawUrgensi)) {
                $urgensi = match (strtoupper($rawUrgensi)) {
                    'DARURAT' => 3,
                    'PENTING' => 2,
                    'BIASA' => 1,
                    default => 1,
                };
            }

            // Convert urgensi to float
            $urgensi = (float) $urgensi;

            // Log data for debugging
            Log::info("Alternative data for {$role->nama_role} - {$facility->nama_fasilitas}:", [
                'user_count' => $userCount,
                'urgensi' => $urgensi,
            ]);

            // Separate facilities based on user_count
            $alternative = [
                'name' => $facility->nama_fasilitas,
                'criteria' => [
                    'user_count' => $userCount,
                    'urgensi' => $urgensi,
                ],
            ];

            if ($userCount > 0) {
                $alternativesPromethee[] = new AlternativeDTO(
                    name: $facility->nama_fasilitas,
                    criteria: [
                        'user_count' => $userCount,
                        'urgensi' => $urgensi,
                    ]
                );
            } else {
                $alternativesZeroCount[] = $alternative;
            }
        }

        // Step 4: Calculate PROMETHEE for alternatives with user_count > 0
        $rankedAlternatives = [];
        if (!empty($alternativesPromethee)) {
            $calculator = new PrometheeCalculator();
            $rankedAlternatives = $calculator->calculatePromethee($alternativesPromethee, $weights);
        }

        // Step 5: Sort alternatives with user_count = 0 by urgensi (descending)
        usort($alternativesZeroCount, fn($a, $b) => $b['criteria']['urgensi'] <=> $a['criteria']['urgensi']);

        // Step 6: Combine results and assign ranks for zero-count alternatives
        $finalResults = $rankedAlternatives;
        $lastRank = count($rankedAlternatives); // Start rank for zero-count alternatives

        foreach ($alternativesZeroCount as $zeroCountAlternative) {
            $zeroCountAlternative['criteria']['rank'] = ++$lastRank;
            $finalResults[] = $zeroCountAlternative;
        }

        // Step 7: Check if final results are empty
        if (empty($finalResults)) {
            Log::info("No valid alternatives for role {$role->nama_role}.");
            return response()->json([]);
        }

        // Step 8: Return results
        return response()->json($finalResults);
    }

    public function tesHitungMahasiswa()
    {
        $promethee = new PrometheeCalculator();

        $alternatives[] = [
            new AlternativeDTO('Proyektor A', ['user_count'=> 4, 'urgensi'=> 2]),
            new AlternativeDTO('Proyektor B', ['user_count'=> 5, 'urgensi'=> 2]),
            new AlternativeDTO('Proyektor C', ['user_count'=> 9, 'urgensi'=> 2]),
            new AlternativeDTO('AC', ['user_count'=> 10, 'urgensi'=> 1]),
            new AlternativeDTO('Papan Tulis', ['user_count'=> 3, 'urgensi'=> 3]),
        ];

        return response()->json($promethee->calculatePromethee($alternatives[0], ['user_count' => 0.65, 'urgensi' => 0.35]));
    }

    public function tesHitungDosen()
    {
        $promethee = new PrometheeCalculator();

        $alternatives[] = [
            new AlternativeDTO('Proyektor A', ['user_count'=> 5, 'urgensi'=> 2]),
            new AlternativeDTO('Proyektor B', ['user_count'=> 3, 'urgensi'=> 2]),
            new AlternativeDTO('Proyektor C', ['user_count'=> 9, 'urgensi'=> 2]),
            new AlternativeDTO('AC', ['user_count'=> 2, 'urgensi'=> 1]),
            new AlternativeDTO('Papan Tulis', ['user_count'=> 3, 'urgensi'=> 3]),
        ];

        return response()->json($promethee->calculatePromethee($alternatives[0], ['user_count' => 0.65, 'urgensi' => 0.35]));
    }

    public function tesHitungTendik()
    {
        $promethee = new PrometheeCalculator();

        $alternatives[] = [
            new AlternativeDTO('Proyektor A', ['user_count'=> 6, 'urgensi'=> 2]),
            new AlternativeDTO('Proyektor B', ['user_count'=> 2, 'urgensi'=> 2]),
            new AlternativeDTO('Proyektor C', ['user_count'=> 8, 'urgensi'=> 2]),
            new AlternativeDTO('AC', ['user_count'=> 5, 'urgensi'=> 1]),
            new AlternativeDTO('Papan Tulis', ['user_count'=> 3, 'urgensi'=> 3]),
        ];

        return response()->json($promethee->calculatePromethee($alternatives[0], ['user_count' => 0.65, 'urgensi' => 0.35]));
    }
}