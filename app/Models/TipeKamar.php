<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class TipeKamar extends Model
{
    protected $fillable = ['area_id', 'nama', 'kode', 'harga', 'fasilitas'];
    protected $collection = 'tipe_kamar';
    // protected $with = ['area'];
    // protected $hidden = ['kamar'];

    public function area()
    {
        return $this->belongsTo(AreaKos::class);
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id', '_id')->orderBy('status', 'asc');
    }

    public function getIsAllowDeleteAttribute()
    {
        return $this->kamar->count() == 0;
    }

    public function getStatistikAttribute()
    {
        return [
            'jumlah_kamar' => $this->kamar->count() ?? 0,
            'jumlah_kamar_kosong' => $this->kamar->where('status', Kamar::STATUS_KOSONG)->count() ?? 0,
            'estimasi_pendapatan' => $this->kamar->where('status', Kamar::STATUS_TERISI)->sum('harga') ?? 0,
        ];
    }

    public function getStatusAttribute()
    {
        // kosong atau terisi
        $kamar = $this->kamar;
        $kamarTerisi = $kamar->where('status', Kamar::STATUS_TERISI)->count();
        $kamarKosong = $kamar->where('status', Kamar::STATUS_KOSONG)->count();
        $selisih = $kamarKosong - $kamarTerisi;

        if ($kamarTerisi == 0) {
            return 'Kosong';
        }
        if ($selisih == 0) {
            return 'Full';
        } else if ($selisih > 0) {
            return 'Terisi Sebagian';
        } else {
            return 'Kosong';
        }
    }

    public function getTotalKamarAttribute()
    {
        return $this->kamar->count();
    }
}
