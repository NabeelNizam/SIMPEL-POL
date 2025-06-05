<?php

namespace App\Http\Helpers;

final class AlternativeDTO
{
    /**
     * Name or code of the alternative.
     * @var string
     */
    public readonly string $name;
    /**
     * Criteria and its value. e.g. ['price' => 0.3, 'quality' => 0.7]
     * @var mixed
     */
    public readonly array $criteria;
    public function __construct(string $name, array $criteria)
    {
        $this->name = $name;
        $this->criteria = $criteria;
    }

    /**
     * Normalize alternatives across all decision makers.
     *
     * Ensures every DM's list includes all alternatives, using INF rank for missing ones.
     *
     * @param array $dmResults Array of arrays of AlternativeDTO (one array per DM).
     * @return array Normalized DM results.
     */
    public static function normalizeAlternatives(array $dmResults): array
    {
        $allNames = [];

        // Collect all unique alternative names from all DMs
        foreach ($dmResults as $result) {
            foreach ($result as $dto) {
                $allNames[$dto->name] = true;
            }
        }

        $allNames = array_keys($allNames); // e.g. ['1', '2', '3', '4', '5']

        // Pad missing alternatives in each DM result
        foreach ($dmResults as $dmIndex => $result) {
            $currentNames = array_map(fn($dto) => $dto->name, $result);
            $missing = array_diff($allNames, $currentNames);

            foreach ($missing as $missingName) {
                $dmResults[$dmIndex][] = new AlternativeDTO($missingName, ['rank' => INF]);
            }

            // Optional: sort by name for consistency
            usort($dmResults[$dmIndex], fn($a, $b) => strcmp($a->name, $b->name));
        }

        return $dmResults;
    }
}
