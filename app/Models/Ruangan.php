<?php

namespace App\Models;

use Faker\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kondisi;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $guarded = ['id_ruangan'];

    protected $primaryKey = 'id_ruangan';
    private $faker = null;
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'id_ruangan', 'id_ruangan');
    }

    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'id_lantai', 'id_lantai');
    }
    public function generateKode()
    {
        $nama = $this->nama_ruangan;
        $this->kode_ruangan = substr($nama, 0, 2) . $this->faker->unique()->randomNumber(3);
    }
}
