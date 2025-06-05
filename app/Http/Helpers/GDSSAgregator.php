<?php
namespace App\Http\Helpers;

class GDSSAgregator
{
    /**
     * Data of alternatives that are intended for the GDSS system.
     * @var array
     */
    public array $alternatives;
    /**
     * Constructor to initialize the GDSS data.
     */
    public function __construct(array $alternatives = [])
    {
        $this->alternatives = $alternatives;
    }
    public function resetGDSS()
    {
        $this->alternatives = [];
    }
}
