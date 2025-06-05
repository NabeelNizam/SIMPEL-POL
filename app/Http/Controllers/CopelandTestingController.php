<?php

namespace App\Http\Controllers;

use App\Http\Helpers\AlternativeDTO;
use App\Http\Helpers\CopelandAggregator;
use Illuminate\Http\Request;

class CopelandTestingController extends Controller
{
    public function __invoke()
    {
        $copeland = new CopelandAggregator();

        // dm1
        $copeland->alternatives[] = [
            new AlternativeDTO('A', ['rank' => 1]),
            new AlternativeDTO('B', ['rank' => 2]),
            new AlternativeDTO('C', ['rank' => 3]),
            new AlternativeDTO('D', ['rank' => 4]),
            new AlternativeDTO('E', ['rank' => 5]),
            new AlternativeDTO('F', ['rank' => 6]),
        ];
        // dm2
        $copeland->alternatives[] = [
            new AlternativeDTO('A', ['rank' => 2]),
            new AlternativeDTO('B', ['rank' => 1]),
            new AlternativeDTO('C', ['rank' => 3]),
            new AlternativeDTO('D', ['rank' => 5]),
            new AlternativeDTO('E', ['rank' => 4]),
            new AlternativeDTO('F', ['rank' => 6]),
        ];
        // dm3
        $copeland->alternatives[] = [
            new AlternativeDTO('A', ['rank' => 2]),
            new AlternativeDTO('B', ['rank' => 1]),
            new AlternativeDTO('C', ['rank' => 3]),
            new AlternativeDTO('D', ['rank' => 4]),
            new AlternativeDTO('E', ['rank' => 6]),
            new AlternativeDTO('F', ['rank' => 5]),
        ];
        return $copeland->run(); // Laravel will return JSON

    }
}
