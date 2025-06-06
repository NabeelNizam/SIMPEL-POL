<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_role';
    protected $table = 'roles';
    protected $fillable = ['kode_role', 'nama_role'];

    protected $guarded = [];

    public function user():HasMany
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}
