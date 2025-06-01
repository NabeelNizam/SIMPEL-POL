<?php
namespace App\Http\Enums;

enum Urgensi: string
{
    case DARURAT = 'DARURAT';
    case PENTING = 'PENTING';
    case BIASA = 'BIASA';
}
