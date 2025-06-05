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
            new AlternativeDTO('A1', ['rank'=> 5]),
            new AlternativeDTO('A2', ['rank'=> 3]),
            new AlternativeDTO('A3', ['rank'=> 1]),
            new AlternativeDTO('A4', ['rank'=> 2]),
            new AlternativeDTO('A5', ['rank'=> 4]),
        ];

        // dm2
        $copeland->alternatives[] = [
            new AlternativeDTO('A1', ['rank'=> 2]),
            new AlternativeDTO('A2', ['rank'=> 3]),
            new AlternativeDTO('A3', ['rank'=> 1]),
            new AlternativeDTO('A4', ['rank'=> 4]),
            new AlternativeDTO('A5', ['rank'=> 5]),
        ];
        // dm3
        $copeland->alternatives[] = [
            new AlternativeDTO('A1', ['rank'=> 2]),
            new AlternativeDTO('A2', ['rank'=> 5]),
            new AlternativeDTO('A3', ['rank'=> 1]),
            new AlternativeDTO('A4', ['rank'=> 3]),
            new AlternativeDTO('A5', ['rank'=> 4]),
        ];

        return $copeland->run(); // Laravel will return JSON

    }
}
