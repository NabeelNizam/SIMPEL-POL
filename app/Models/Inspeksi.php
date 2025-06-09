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
    protected $appends = ['status_aduan'];

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
    public function getStatusAduanAttribute()
    {
        // return $this->perbaikan?->status;
        $aduan = Aduan::query()->whereHas('fasilitas', function ($q) {
            $q->whereHas('inspeksi', function ($q) {
                $q->where('id_inspeksi', $this->id_inspeksi);
            });
        });
        return $aduan->first()?->status;
    }
    public function getUserCountAttribute()
    {
        return Aduan::where('id_fasilitas', $this->id_fasilitas)
            ->whereHas('periode', function ($query) {
                $query->where('tanggal_selesai', '<=', $this->periode->tanggal_selesai);
            })
            ->count();
    }
}
