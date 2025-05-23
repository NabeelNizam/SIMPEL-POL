<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $primaryKey = 'id_user';
    protected $table = 'users';
    protected $fillable = ['username', 'password', 'id_role', 'email', 'name'];
    protected $with = ['role'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
    public function getRole()
{
    return optional($this->role)->nama_role;
}

    public function hasRole($role)
    {
        return $this->role->nama_role == $role;
    }

    public function identifier(): BelongsTo
    {
        if ($this->role == 'MAHASISWA') {
            // return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user');
            return $this->belongsTo(Mahasiswa::class, 'id_user', 'id_user');
        }
        // return $this->hasOne(Pegawai::class, 'id_user', 'id_user');
        return $this->belongsTo(Pegawai::class, 'id_user', 'id_user');
    }
    
}
