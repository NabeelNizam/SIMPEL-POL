<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioritas extends Model
{
    use HasFactory;
    protected $table = 'prioritas';
    protected $primaryKey = 'id_prioritas';
    protected $guarded = [];

    public function aduan()
    {
        return $this->belongsTo(Aduan::class, 'id_aduan', 'id_aduan');
    }
}
