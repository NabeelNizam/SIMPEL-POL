<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log;

class PrometheeCalculator
{
    /**
     * Calculate PROMETHEE II ranking and add rank, leaving flow, entering flow, and net flow to criteria.
     *
     * @param AlternativeDTO[] $alternatives
     * @param array $weights ['user_count' => 0.5, 'urgensi' => 0.5]
     * @return array Alternatives with rank and flows added to criteria
     */
    public function calculatePromethee(array $alternatives, array $weights): array
    {
        // Check if alternatives array is empty
        if (empty($alternatives)) {
            Log::error('No alternatives provided for PROMETHEE calculation.');
            return [];
        }

        $n = count($alternatives);
        $criteria = array_keys($alternatives[0]->criteria ?? ['user_count', 'urgensi']);
        $preferenceMatrix = [];

        // Step 1: Calculate preference for each pair of alternatives
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) continue;
                $pi = 0;
                foreach ($criteria as $criterion) {
                    // Check if criterion exists
                    if (!isset($alternatives[$i]->criteria[$criterion]) || !isset($alternatives[$j]->criteria[$criterion])) {
                        Log::warning("Missing criterion '$criterion' for alternative {$alternatives[$i]->name} or {$alternatives[$j]->name}");
                        continue;
                    }
                    $diff = $alternatives[$i]->criteria[$criterion] - $alternatives[$j]->criteria[$criterion];
                    // Usual function: preferensi = 1 jika diff > 0, else 0 (benefit criteria)
                    $preference = $diff > 0 ? 1 : 0;
                    $pi += ($weights[$criterion] ?? 0) * $preference;
                }
                $preferenceMatrix[$i][$j] = $pi;
            }
        }

        // Step 2: Calculate positive (leaving) and negative (entering) flows
        $leavingFlows = [];
        $enteringFlows = [];
        $netFlows = [];
        for ($i = 0; $i < $n; $i++) {
            // Leaving Flow (φ⁺): Sum of preferences of ai over others, normalized
            $leavingFlows[$i] = array_sum($preferenceMatrix[$i] ?? []);
            $leavingFlows[$i] /= ($n - 1) ?: 1;

            // Entering Flow (φ⁻): Sum of preferences of others over ai, normalized
            $enteringFlows[$i] = 0;
            for ($j = 0; $j < $n; $j++) {
                $enteringFlows[$i] += ($preferenceMatrix[$j][$i] ?? 0);
            }
            $enteringFlows[$i] /= ($n - 1) ?: 1;

            // Net Flow (φ): Leaving Flow - Entering Flow
            $netFlows[$i] = [
                'index' => $i,
                'net_flow' => $leavingFlows[$i] - $enteringFlows[$i],
            ];
        }

        // Step 3: Sort by net flow (descending for benefit criteria)
        usort($netFlows, fn($a, $b) => $b['net_flow'] <=> $a['net_flow']);

        // Step 4: Add rank and flows to alternatives' criteria
        $results = [];
        foreach ($netFlows as $rank => $netFlow) {
            $index = $netFlow['index'];
            $alternative = $alternatives[$index];
            // Create new criteria array with rank and flows
            $newCriteria = $alternative->criteria;
            $newCriteria['leaving_flow'] = round($leavingFlows[$index], 4); // Round for readability
            $newCriteria['entering_flow'] = round($enteringFlows[$index], 4);
            $newCriteria['net_flow'] = round($netFlow['net_flow'], 4);
            $newCriteria['rank'] = $rank + 1; // Rank starts from 1
            // $results[] = [
            //     'name' => $alternative->name,
            //     'criteria' => $newCriteria,
            // ];
            $results[] = new AlternativeDTO(
                name: $alternative->name,
                criteria: $newCriteria
            );
        }

        return $results;
    }
}