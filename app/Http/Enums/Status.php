<?php

namespace App\Http\Enums;

enum Status: string
{
    case MENUNGGU_DIPROSES = 'MENUNGGU_DIPROSES';
    case SEDANG_INSPEKSI = 'SEDANG_INSPEKSI';
    case SEDANG_DIPERBAIKI = 'SEDANG_DIPERBAIKI';
    case SELESAI = 'SELESAI';
    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU_DIPROSES => 'Menunggu Diproses',
            self::SEDANG_INSPEKSI => 'Sedang Inspeksi',
            self::SEDANG_DIPERBAIKI => 'Sedang Diperbaiki',
            self::SELESAI => 'Selesai',
        };
    }
}
