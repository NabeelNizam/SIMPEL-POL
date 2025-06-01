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
        return $this->hasOne(User::class, 'id_user', 'id_user');
    }
    public function fasilitas()
    {
        return $this->hasOne(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
    public function umpan_balik()
    {
        return $this->hasOne(UmpanBalik::class, 'id_aduan', 'id_aduan');
    }
    public function prioritas()
    {
        return $this->hasOne(Prioritas::class, 'id_prioritas', 'id_prioritas');
    }
    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'id_perbaikan', 'id_perbaikan');
    }

}
