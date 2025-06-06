<?php

namespace App\Models;

use App\Http\Enums\JenisKriteria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    protected $fillable = ['bobot'];
    protected $casts =[
        'jenis_kriteria' => JenisKriteria::class,
    ];
}
