<?php

namespace App\Models;

use App\Http\Enums\TingkatKerusakan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspeksi extends Model
{
    use HasFactory;

    protected $table = 'inspeksi';
    protected $primaryKey = 'id_inspeksi';
    protected $guarded =[];

    protected $casts =[
        'tingkat_kerusakan' => TingkatKerusakan::class,
    ];

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_user_teknisi', 'id_user');
    }
    public function sarpras()
    {
        return $this->belongsTo(User::class, 'id_user_sarpras', 'id_user');
    }
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
    public function periode(){
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }

    public function biaya()
    {
        return $this->hasMany(Biaya::class, 'id_inspeksi', 'id_inspeksi');
    }
    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'id_inspeksi', 'id_inspeksi');
    }
public function ruangan()
{
    return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
}
public function kategori()
{
    return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
}

}
