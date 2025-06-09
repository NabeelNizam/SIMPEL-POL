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
    protected $appends = ['fasilitas', 'status_aduan', 'status'];
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'id_inspeksi', 'id_inspeksi');
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }
    public function getFasilitasAttribute()
    {
        return $this->inspeksi?->fasilitas;
    }
    public function getStatusAduanAttribute()
    {
        return $this->inspeksi?->status_aduan;
    }
    public function getStatusAttribute()
    {
        if($this->teknisi_selesai){
            return 'SELESAI';
        }else{
            return 'PROSES';
        }
    }

}
