<?php

namespace App\Models;

use App\Http\Enums\Kondisi;
use App\Http\Enums\Urgensi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';
    protected $guarded = ['id_fasilitas'];
    protected $primaryKey = 'id_fasilitas';

    public function aduan()
    {
        return $this->hasMany(Aduan::class, 'id_fasilitas');
    }
    public function inspeksi()
    {
        return $this->hasMany(Inspeksi::class, 'id_fasilitas', 'id_fasilitas');
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }
    protected $casts = [
        'kondisi' => Kondisi::class,
        'urgensi' => Urgensi::class
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
    public function getLokasiString(): string
    {
        $ruangan = $this->ruangan;
        if ($ruangan) {
            return "{$ruangan->nama_ruangan}, {$ruangan->lantai->nama_lantai}, {$ruangan->lantai->gedung->nama_gedung}";
        }
        return 'Lokasi tidak diketahui';
    }
    public function getLokasiAttribute(): string
    {
        return $this->getLokasiString();
    }
}
