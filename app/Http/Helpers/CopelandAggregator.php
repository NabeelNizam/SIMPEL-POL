<?php

namespace App\Http\Helpers;

class CopelandAggregator
{
    /**
     * Array 2D untuk menyusun array AlternativeDTO dari banyak Decision Maker.
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
    /**
     * Runs the Copeland aggregation and returns ranked alternatives.
     * @return array<int, AlternativeDTO[]>
     */
    public function run(): array
    {
        // hitung dm
        $dmCount = count($this->alternatives);
        if ($dmCount === 0) return [];

        // pisahkan array nama
        $names = array_map(fn($alt) => $alt->name, $this->alternatives[0]);

        // buat array assoc untuk menyimpan skor
        $scores = array_fill_keys($names, 0);

        foreach ($names as $a) {
            foreach ($names as $b) {
                // bypass perbandingan alternatif yang sama
                if ($a === $b) continue;

                $aWins = 0;
                $bWins = 0;

                foreach ($this->alternatives as $dmRanking) {
                    $ranks = [];

                    foreach ($dmRanking as $dto) {
                        // isi array assoc key dengan nama dan value dengan rank
                        $ranks[$dto->name] = $dto->criteria['rank'] ?? INF;
                        // gunakan infinity jika tidak ada rank
                    }

                    $aRank = $ranks[$a] ?? INF;
                    $bRank = $ranks[$b] ?? INF;

                    if ($aRank < $bRank) {
                        $aWins++;
                    } elseif ($aRank > $bRank) {
                        $bWins++;
                    }
                    // else seri, skip
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
            $result[] = new AlternativeDTO($name, ['score' => $score]);
        }
        usort($result, fn($a, $b) => $b->criteria['score'] <=> $a->criteria['score']);
        return $result;
    }
}
