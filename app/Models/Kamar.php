<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Mongodb\Eloquent\Model;

class Kamar extends Model
{
    protected $collection = 'kamar';
    protected $fillable = ['area_id', 'nama', 'nomor', 'lantai', 'tipe_kamar_id', 'harga', 'fasilitas', 'deskripsi', 'foto', 'status', 'penyewa_id', 'durasi'];

    protected $hidden = ['foto'];
    protected $appends = ['foto_url'];

    const IMAGE_PATH = 'foto_kamar';
    const STATUS_KOSONG = 'Kosong';
    const STATUS_TERISI = 'Terisi';
    const STATUS = [self::STATUS_KOSONG, self::STATUS_TERISI];

    public function getFotoUrlAttribute()
    {
        if (!empty($this->foto)) {
            return array_map(fn ($foto) => url(Storage::url($foto)), $this->foto);
        }
        return [];
    }

    public function getIsAllowDeleteAttribute()
    {
        return $this->sewa->count() == 0 && $this->riwayat->count() == 0;
    }

    public function getOptionLabelAttribute()
    {
        return $this->area->judul . ' - ' . $this->nama . ' - LT ' . $this->lantai . ' - NO ' . $this->nomor;
    }

    public function area()
    {
        return $this->belongsTo(AreaKos::class);
    }

    public function tipe_kamar()
    {
        return $this->belongsTo(TipeKamar::class);
    }

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }

    public function sewa()
    {
        return $this->hasMany(Sewa::class, 'kamar_id', '_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatKamar::class, 'kamar_id', '_id');
    }
}
