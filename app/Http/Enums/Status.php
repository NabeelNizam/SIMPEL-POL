<?php

namespace App\Http\Enums;

enum Status: string
{
    case MENUNGGU_DIPROSES = 'MENUNGGU_DIPROSES';
    case SEDANG_INSPEKSI = 'SEDANG_INSPEKSI';
    case SEDANG_DIPERBAIKI = 'SEDANG_DIPERBAIKI';
    case SELESAI = 'SELESAI';
}
