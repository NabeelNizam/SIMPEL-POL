<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    protected $table = 'perbaikan';
    protected $guarded = ['id_perbaikan'];
    protected $primaryKey = 'id_perbaikan';
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'id_inspeksi', 'id_inspeksi');
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }
     public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
public function biaya()
    {
        return $this->hasMany(Biaya::class, 'id_perbaikan', 'id_perbaikan');
    }
}
