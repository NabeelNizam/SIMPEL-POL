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

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class);
    }
}
