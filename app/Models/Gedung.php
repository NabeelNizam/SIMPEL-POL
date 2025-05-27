<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'gedung';
    protected $primaryKey = 'id_gedung';

    public function lantai()
    {
        return $this->hasMany(Lantai::class);
    }
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

}
