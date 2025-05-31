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
    public function aduan()
    {
        return $this->belongsTo(Aduan::class, 'id_aduan', 'id_aduan');
    }
    public function biaya()
    {
        return $this->hasMany(Biaya::class, 'id_perbaikan', 'id_perbaikan');
    }
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_user_teknisi', 'id_user');
    }

}
