<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log;

class PrometheeCalculator
{
    /**
     * Calculate PROMETHEE II ranking and add rank to criteria.
     *
     * @param AlternativeDTO[] $alternatives
     * @param array $weights ['user_count' => 0.5, 'urgensi' => 0.5]
     * @return array Alternatives with rank added to criteria
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

        // Step 2: Calculate positive and negative flows
        $positiveFlows = [];
        $negativeFlows = [];
        for ($i = 0; $i < $n; $i++) {
            $positiveFlows[$i] = array_sum($preferenceMatrix[$i] ?? []);
            $negativeFlows[$i] = 0;
            for ($j = 0; $j < $n; $j++) {
                $negativeFlows[$i] += ($preferenceMatrix[$j][$i] ?? 0);
            }
            // Normalize flows (avoid division by zero)
            $positiveFlows[$i] /= ($n - 1) ?: 1;
            $negativeFlows[$i] /= ($n - 1) ?: 1;
        }

        // Step 3: Calculate net flow and assign ranks
        $netFlows = [];
        for ($i = 0; $i < $n; $i++) {
            $netFlow = $positiveFlows[$i] - $negativeFlows[$i];
            $netFlows[$i] = [
                'index' => $i,
                'net_flow' => $netFlow,
            ];
        }

        // Sort by net flow (descending for benefit criteria)
        usort($netFlows, fn($a, $b) => $b['net_flow'] <=> $a['net_flow']);

        // Step 4: Add rank to alternatives' criteria
        $results = [];
        foreach ($netFlows as $rank => $netFlow) {
            $index = $netFlow['index'];
            $alternative = $alternatives[$index];
            // Create new criteria array with rank
            $newCriteria = $alternative->criteria;
            $newCriteria['rank'] = $rank + 1; // Rank starts from 1
            $results[] = [
                'name' => $alternative->name,
                'criteria' => $newCriteria,
            ];
        }

        return $results;
    }
}