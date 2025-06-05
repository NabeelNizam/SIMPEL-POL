<?php

namespace App\Http\Helpers;

class CopelandAggregator
{
    /**
     * A 2D array of alternatives from multiple decision makers.
     * [
     *   [AlternativeDTO, AlternativeDTO, ...], // Decision Maker 1
     *   [AlternativeDTO, AlternativeDTO, ...], // Decision Maker 2
     *   ...
     * ]
     * @var array<int, AlternativeDTO[]>
     */
    public array $alternatives;
    /**
     * Constructor to initialize the GDSS data.
     */
    public function __construct(array $alternatives = [])
    {
        $this->alternatives = $alternatives;
    }
    /**
     * Reset the GDSS data.
     * This method clears the alternatives array.
     */
    public function resetGDSS()
    {
        $this->alternatives = [];
    }

    public function addAlternative(string $name, array $criteria)
    {
        $this->alternatives[] = new AlternativeDTO($name, $criteria);
    }

    /**
     * Runs the Copeland aggregation and returns ranked alternatives.
     * @return array<int, array{name: string, score: int}>
     */
    public function run(): array
    {
        $dmCount = count($this->alternatives);
        if ($dmCount === 0) return [];

        $names = array_map(fn($alt) => $alt->name, $this->alternatives[0]);
        $scores = array_fill_keys($names, 0);

        foreach ($names as $a) {
            foreach ($names as $b) {
                if ($a === $b) continue;

                $aWins = 0;
                $bWins = 0;

                foreach ($this->alternatives as $dmRanking) {
                    $ranks = [];

                    // Build rank lookup from DM
                    foreach ($dmRanking as $dto) {
                        $ranks[$dto->name] = $dto->criteria['rank'] ?? INF; // fallback to INF
                    }

                    $aRank = $ranks[$a] ?? INF;
                    $bRank = $ranks[$b] ?? INF;

                    if ($aRank < $bRank) {
                        $aWins++;
                    } elseif ($aRank > $bRank) {
                        $bWins++;
                    }
                    // else tie
                }

                if ($aWins > $bWins) {
                    $scores[$a] += 1;
                } elseif ($aWins < $bWins) {
                    $scores[$a] -= 1;
                }
            }
        }

        // Convert and sort result
        $result = [];
        foreach ($scores as $name => $score) {
            $result[] = ['name' => $name, 'score' => $score];
        }

        usort($result, fn($a, $b) => $b['score'] <=> $a['score']);
        return $result;
    }
}
