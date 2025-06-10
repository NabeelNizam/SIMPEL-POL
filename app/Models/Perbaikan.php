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
    protected $appends = ['fasilitas', 'status_aduan', 'status', 'teknisi_selesai', 'sarpras_selesai'];
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
        if($this->tanggal_selesai != null){
            return 'SELESAI';
        }else{
            return 'PROSES';
        }
    }
    public function getTeknisiSelesaiAttribute()
    {
         if($this->tanggal_selesai != null){
            return true;
        }else{
            return false;
        }
    }
    public function getSarprasSelesaiAttribute()
    {
        
    }

}
