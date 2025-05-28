<?php

namespace App\Http\Enums;

enum Status: string
{
    case MENUNGGU_VERIFIKASI = 'MENUNGGU_VERIFIKASI';
    case SEDANG_INSPEKSI = 'SEDANG_INSPEKSI';
    case SEDANG_DIPERBAIKI = 'SEDANG_DIPERBAIKI';
    case SELESAI = 'SELESAI';
}
