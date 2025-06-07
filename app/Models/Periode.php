<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periode';
    protected $primaryKey = 'id_periode';

    protected $fillable = [
        'kode_periode',
        'tanggal_mulai',
        'tanggal_selesai'
    ];
    
    public static function getPeriodeAktif()
    {
        $tanggalSekarang = Carbon::now();
        return self::where('tanggal_mulai', '<=', $tanggalSekarang)
                   ->where('tanggal_selesai', '>=', $tanggalSekarang)
                   ->first();
    }
}
