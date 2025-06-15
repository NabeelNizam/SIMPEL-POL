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
    protected $appends = ['fasilitas', 'status_aduan', 'status', 'teknisi_selesai', 'sarpras_selesai'];
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'id_inspeksi', 'id_inspeksi');
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'id_periode', 'id_periode');
    }
    public function getFasilitasAttribute()
    {
        return $this->inspeksi?->fasilitas;
    }
    public function getStatusAduanAttribute()
    {
        return $this->inspeksi?->status_aduan;
    }
    public function getStatusAttribute()
    {
        if ($this->tanggal_selesai != null) {
            return 'SELESAI';
        } else {
            return 'PROSES';
        }
    }
    public function getTeknisiSelesaiAttribute()
    {
        if ($this->tanggal_selesai != null) {
            return true;
        } else {
            return false;
        }
    }
    public function getSarprasSelesaiAttribute()
    {

    } 
protected function buildAduanTertanganiQuery()
    {
        // Ambil id_fasilitas dari relasi inspeksi
        $idFasilitas = $this->inspeksi->id_fasilitas;
        $currentWaktuSelesai = $this->periode->tanggal_selesai;

        // Cari perbaikan sebelumnya dengan id_fasilitas yang sama (melalui inspeksi)
        $previousPerbaikan = Perbaikan::whereHas('inspeksi', function ($query) use ($idFasilitas) {
                $query->where('id_fasilitas', $idFasilitas);
            })
            ->whereHas('periode', function ($query) use ($currentWaktuSelesai) {
                $query->where('tanggal_selesai', '<', $currentWaktuSelesai);
            })
            // ->with('periode')
            ->orderByDesc('tanggal_selesai')
            ->first();

        $waktuBawah = $previousPerbaikan ? $previousPerbaikan->periode->tanggal_selesai : null;

        // Query untuk aduan
        $query = Aduan::where('id_fasilitas', $idFasilitas)
            ->whereHas('periode', function ($query) use ($currentWaktuSelesai) {
                $query->where('tanggal_selesai', '<', $currentWaktuSelesai);
            });

        if ($waktuBawah) {
            $query->whereHas('periode', function ($query) use ($waktuBawah) {
                $query->where('tanggal_selesai', '>', $waktuBawah);
            });
        }

        return $query;
    }

    // Accessor untuk koleksi aduan tertangani
    public function getAduanTertanganiAttribute()
    {
        return $this->buildAduanTertanganiQuery()->get();
    }

    // Accessor untuk jumlah aduan tertangani
    public function getJumlahAduanTertanganiAttribute()
    {
        return $this->buildAduanTertanganiQuery()->count();
    }
    public function getUmpanBalikTertanganiAttribute()
    {
        $aduanIds = $this->buildAduanTertanganiQuery()->pluck('id_aduan')->toArray();
        return UmpanBalik::whereIn('id_aduan', $aduanIds)->get();
    }
    public function getJumlahUmpanBalikTertanganiAttribute()
    {
        $aduanIds = $this->buildAduanTertanganiQuery()->pluck('id_aduan')->toArray();
        return UmpanBalik::whereIn('id_aduan', $aduanIds)->count();
    }

    public function getRataRataRatingAttribute()
    {
        $aduanIds = $this->buildAduanTertanganiQuery()->pluck('id_aduan')->toArray();
        return UmpanBalik::whereIn('id_aduan', $aduanIds)->avg('rating');
    }
}
