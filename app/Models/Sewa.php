<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Sewa extends Model
{
    protected $collection = 'sewa';
    protected $fillable = ['area_id', 'kamar_id', 'penyewa_id', 'tanggal_bayar', 'total_bayar', 'durasi', 'is_active', 'catatan'];
    protected $dates = ['created_at', 'updated_at', 'tanggal_bayar'];


    const DURASI_SEWA_BULANAN = "Bulanan";
    const DURASI_SEWA_TAHUNAN = "Tahunan";
    const DURASI_SEWA = [
        self::DURASI_SEWA_BULANAN,
        self::DURASI_SEWA_TAHUNAN,
    ];

    const STATUS_SEWA_BARU = 'Baru';
    const STATUS_SEWA_PINDAH = 'Pindah';
    const STATUS_SEWA = [
        self::STATUS_SEWA_BARU,
        self::STATUS_SEWA_PINDAH,
    ];

    public function getDurasiFormatAttribute()
    {
        return $this->durasi == self::DURASI_SEWA_BULANAN ? 'Bulan' : 'Tahun';
    }


    public function area()
    {
        return $this->belongsTo(AreaKos::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeNonActive($query)
    {
        return $query->where('is_active', 0);
    }
}
