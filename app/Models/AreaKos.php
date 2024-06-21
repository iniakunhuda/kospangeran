<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class AreaKos extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'jenis', 'alamat'];

    public function getStatistikAttribute()
    {
        return [
            'jumlah_kamar' => $this->kamar->count() ?? 0,
            'jumlah_kamar_kosong' => $this->kamar->where('status', Kamar::STATUS_KOSONG)->count() ?? 0,
            'estimasi_pendapatan' => $this->kamar->where('status', Kamar::STATUS_TERISI)->sum('harga') ?? 0,
        ];
    }

    public function getIsAllowDeleteAttribute()
    {
        return $this->tipe_kamar->count() == 0 || $this->kamar->count() == 0;
    }

    public function tipe_kamar()
    {
        return $this->hasMany(TipeKamar::class, 'area_id', '_id');
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'area_id', '_id');
    }
}
