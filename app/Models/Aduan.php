<?php

namespace App\Models;

use App\Http\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduan';

    protected $guarded = [];
    protected $primaryKey = 'id_aduan';
    protected $casts = [
        'status' => Status::class
    ];
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_user_pelapor', 'id_user');
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
    public function umpan_balik()
    {
        return $this->hasOne(UmpanBalik::class, 'id_aduan', 'id_aduan');
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }
    public function biaya()
    {
        return $this->hasManyThrough(Biaya::class, Perbaikan::class, 'id_perbaikan', 'id_perbaikan', 'id_perbaikan', 'id_perbaikan');
    }
}
