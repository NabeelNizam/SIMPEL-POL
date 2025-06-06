<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    use HasFactory;

    protected $table = 'lantai';

    protected $guarded = [];
    protected $primaryKey = 'id_lantai';
    
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'id_gedung');
    }

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_lantai', 'id_lantai');
    }

}
