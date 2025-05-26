<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jurusan';
    protected $table = 'jurusan';
    protected $guarded = [];

    public function users():HasMany
    {
        return $this->hasMany(User::class, 'id_jurusan', 'id_jurusan');
    }
    public function gedung():HasMany
    {
        return $this->hasMany(Gedung::class, 'id_jurusan', 'id_jurusan');
    }
}
