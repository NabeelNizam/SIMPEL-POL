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
    public function __construct(string $name, array $criteria) {
        $this->name = $name;
        $this->criteria = $criteria;
    }
}
