<?php

namespace App\Http\Helpers;

class PrometheeTes
{
    public static function calculate($criteria, $alternatives)
    {
        // Step 1: Normalize the values
        $normalized = self::normalizeValues($criteria, $alternatives);

        // Step 2: Calculate pairwise differences
        $pairwiseDiff = self::calculatePairwiseDifferences($normalized);

        // Step 3: Apply preference function (using usual criterion)
        $preference = self::applyPreferenceFunction($pairwiseDiff);

        // Step 4: Calculate preference indices
        $preferenceIndices = self::calculatePreferenceIndices($criteria, $preference);

        // Step 5: Calculate leaving and entering flows
        $flows = self::calculateFlows($preferenceIndices, count($alternatives));

        // Step 6: Calculate net flow and ranking
        $result = self::calculateNetFlowAndRanking($flows, $alternatives);

        return $result;
    }

    private static function normalizeValues($criteria, $alternatives)
    {
        $normalized = [];
        $numCriteria = count($criteria);

        // Validasi: Pastikan setiap alternatif memiliki jumlah nilai yang sesuai
        foreach ($alternatives as $i => $alt) {
            if (count($alt['values']) !== $numCriteria) {
                throw new \Exception("Alternatif '{$alt['id']}' memiliki jumlah nilai yang tidak sesuai. Diharapkan: $numCriteria, ditemukan: " . count($alt['values']));
            }
        }

        // Untuk setiap kriteria
        for ($j = 0; $j < $numCriteria; $j++) {
            // Ambil nilai untuk kriteria ke-j dari semua alternatif
            $values = array_column($alternatives, 'values');
            $criterionValues = array_column($values, $j); // Ambil nilai untuk kriteria ke-j
            $min = min($criterionValues);
            $max = max($criterionValues);

            foreach ($alternatives as $i => $alt) {
                $value = $alt['values'][$j];
                if ($criteria[$j]['type'] === 'BENEFIT') {
                    // Normalize untuk BENEFIT: (x - min) / (max - min)
                    $normalized[$i][$j] = ($max == $min) ? 0 : ($value - $min) / ($max - $min);
                } else {
                    // Normalize untuk COST: (max - x) / (max - min)
                    $normalized[$i][$j] = ($max == $min) ? 0 : ($max - $value) / ($max - $min);
                }
            }
        }

        return $normalized;
    }

    private static function calculatePairwiseDifferences($normalized)
    {
        $numAlternatives = count($normalized);
        $numCriteria = count($normalized[0]);
        $pairwiseDiff = [];

        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($k = 0; $k < $numAlternatives; $k++) {
                if ($i != $k) {
                    for ($j = 0; $j < $numCriteria; $j++) {
                        $pairwiseDiff[$i][$k][$j] = $normalized[$i][$j] - $normalized[$k][$j];
                    }
                }
            }
        }

        return $pairwiseDiff;
    }

    private static function applyPreferenceFunction($pairwiseDiff)
    {
        $preference = [];
        $numAlternatives = count($pairwiseDiff);
        $numCriteria = count($pairwiseDiff[0][array_key_first($pairwiseDiff[0])]);

        // Usual criterion: P(d) = 0 if d <= 0, P(d) = 1 if d > 0
        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($k = 0; $k < $numAlternatives; $k++) {
                if ($i != $k) {
                    for ($j = 0; $j < $numCriteria; $j++) {
                        $preference[$i][$k][$j] = ($pairwiseDiff[$i][$k][$j] > 0) ? 1 : 0;
                    }
                }
            }
        }

        return $preference;
    }

    private static function calculatePreferenceIndices($criteria, $preference)
    {
        $preferenceIndices = [];
        $numAlternatives = count($preference);
        $numCriteria = count($criteria);

        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($k = 0; $k < $numAlternatives; $k++) {
                if ($i != $k) {
                    $sum = 0;
                    for ($j = 0; $j < $numCriteria; $j++) {
                        $sum += $criteria[$j]['weight'] * $preference[$i][$k][$j];
                    }
                    $preferenceIndices[$i][$k] = $sum;
                } else {
                    $preferenceIndices[$i][$k] = 0;
                }
            }
        }

        return $preferenceIndices;
    }

    private static function calculateFlows($preferenceIndices, $numAlternatives)
    {
        $flows = [
            'leaving' => [],
            'entering' => []
        ];

        // Calculate leaving flow (sum of preference indices for each alternative)
        for ($i = 0; $i < $numAlternatives; $i++) {
            $sum = 0;
            for ($k = 0; $k < $numAlternatives; $k++) {
                $sum += $preferenceIndices[$i][$k];
            }
            $flows['leaving'][$i] = $sum / ($numAlternatives - 1);
        }

        // Calculate entering flow (sum of preference indices against each alternative)
        for ($k = 0; $k < $numAlternatives; $k++) {
            $sum = 0;
            for ($i = 0; $i < $numAlternatives; $i++) {
                $sum += $preferenceIndices[$i][$k];
            }
            $flows['entering'][$k] = $sum / ($numAlternatives - 1);
        }

        return $flows;
    }

    private static function calculateNetFlowAndRanking($flows, $alternatives)
    {
        $result = [];
        $numAlternatives = count($alternatives);

        // Calculate net flow and prepare result
        for ($i = 0; $i < $numAlternatives; $i++) {
            $netFlow = $flows['leaving'][$i] - $flows['entering'][$i];
            // Menyertakan semua field dari $alternatives, termasuk field tambahan
            $result[] = array_merge(
                [
                    'id' => $alternatives[$i]['id'],
                    'leaving_flow' => round($flows['leaving'][$i], 3),
                    'entering_flow' => round($flows['entering'][$i], 3),
                    'net_flow' => round($netFlow, 3),
                ],
                // Salin semua field lain dari alternatif
                array_diff_key($alternatives[$i], ['id' => null, 'values' => null])
            );
        }

        // Sort by net flow (descending) to determine ranking
        usort($result, function ($a, $b) {
            return $b['net_flow'] <=> $a['net_flow'];
        });

        // Assign ranking
        foreach ($result as $index => &$item) {
            $item['ranking'] = $index + 1;
        }

        return $result;
    }
}