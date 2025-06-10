<?php

namespace App\Models;

use App\Http\Enums\Status;
use App\Http\Enums\TingkatKerusakan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspeksi extends Model
{
    use HasFactory;

    protected $table = 'inspeksi';
    protected $primaryKey = 'id_inspeksi';
    protected $guarded =[];

    protected $casts =[
        'tingkat_kerusakan' => TingkatKerusakan::class,
    ];
    protected $appends = ['status_aduan'];

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_user_teknisi', 'id_user');
    }
    public function sarpras()
    {
        return $this->belongsTo(User::class, 'id_user_sarpras', 'id_user');
    }
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
    public function periode(){
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }

    public function biaya()
    {
        return $this->hasMany(Biaya::class, 'id_inspeksi', 'id_inspeksi');
    }
    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'id_inspeksi', 'id_inspeksi');
    }
    public function getStatusAduanAttribute()
    {
        // return $this->perbaikan?->status;
        $aduan = Aduan::query()->whereHas('fasilitas', function ($q) {
            $q->whereHas('inspeksi', function ($q) {
                $q->where('id_inspeksi', $this->id_inspeksi);
            });
        });
        return $aduan->first()?->status;
    }
    public function getUserCountAttribute()
    {
        return Aduan::where('id_fasilitas', $this->id_fasilitas)
            ->where('status', Status::SEDANG_INSPEKSI->value)
            ->whereHas('periode', function ($query) {
                $query->where('tanggal_selesai', '<', $this->periode->tanggal_selesai);
            })
            ->count();
    }
    public function getSkorLaporanBerulangAttribute()
    {
        $periodeUnik = Aduan::where('id_fasilitas', $this->id_fasilitas)
            ->whereIn('status', [Status::MENUNGGU_DIPROSES->value, Status::SEDANG_INSPEKSI->value])
            ->distinct('id_periode')
            ->count('id_periode');

        $result = $periodeUnik > 1 ? 2 : 1;

        return $result;
    }
    public function getBobotPelaporAttribute()
    {
        $counts = Aduan::where('id_fasilitas', $this->id_fasilitas)
            ->where('status', Status::SEDANG_INSPEKSI->value)
            ->whereHas('periode', function ($query) {
                $query->where('tanggal_selesai', '<', $this->periode->tanggal_selesai);
            })
            ->join('users', 'aduan.id_user_pelapor', '=', 'users.id_user')
            ->whereIn('users.id_role', [1, 5, 6])
            ->groupBy('users.id_role')
            ->selectRaw('users.id_role, COUNT(*) as total')
            ->pluck('total', 'id_role')
            ->toArray();

        $count_mahasiswa = $counts[1] ?? 0; // id_role = 1
        $count_dosen = $counts[5] ?? 0;     // id_role = 5
        $count_tendik = $counts[6] ?? 0;    // id_role = 6
        


        $result = ($count_mahasiswa * 1) + ($count_dosen * 2) + ($count_tendik * 3);

        // $tes_nilai = [
        //     'mahasiswa' => $count_mahasiswa,
        //     'tendik' => $count_tendik,
        //     'dosen' => $count_dosen,
        //     'result' => $result
        // ];
    
        // dd($tes_nilai);

        return $result;
    }
}
