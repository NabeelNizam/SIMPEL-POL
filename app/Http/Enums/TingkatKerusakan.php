<?php
namespace App\Http\Enums;
enum TingkatKerusakan:string
{
    case RINGAN = 'RINGAN';
    case SEDANG = 'SEDANG';
    case PARAH = 'PARAH';

    public function toNumeric(): int
{
    return match ($this) {
        self::RINGAN => 1,
        self::SEDANG => 2,
        self::PARAH => 3,
    };
}
}
