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
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'no_hp',
        'id_jurusan',
        'id_role'
    ];

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
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id_user');
    }

    protected $appends = ['identifier'];

    public function getIdentifierAttribute()
    {
        return $this->role->nama_role == 'MAHASISWA'
            ? $this->mahasiswa?->nim
            : $this->pegawai?->nip;
    }

    public function setIdentifierAttribute($value)
    {
        if ($this->role->nama_role == 'MAHASISWA' && $this->mahasiswa) {
            $this->mahasiswa->update(['nim' => $value]);
        } elseif ($this->pegawai) {
            $this->pegawai->update(['nip' => $value]);
        }
    }

    public function setIdentifier($value)
    {
        if ($this->role->nama_role == 'MAHASISWA' && $this->mahasiswa) {
            $this->mahasiswa->update(['nim' => $value]);
        } elseif ($this->pegawai) {
            $this->pegawai->update(['nip' => $value]);
        }
        return $this;
    }
}
