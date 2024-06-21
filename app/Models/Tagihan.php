<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Tagihan extends Model
{
    protected $collection = 'tagihan';
    protected $fillable = ['area_id', 'kamar_id', 'penyewa_id', 'tanggal_tagihan_dibuat', 'total_tagihan', 'sisa_tagihan', 'durasi', 'status', 'is_deleted'];
    protected $dates = ['created_at', 'updated_at', 'tanggal_tagihan_dibuat'];

    // kategori: (tahunan/bulanan)
    const KATEGORI_BULANAN = "Bulanan";
    const KATEGORI_TAHUNAN = "Tahunan";

    const STATUS_BAYAR_BELUM_BAYAR = 0;
    const STATUS_BAYAR_LUNAS = 1;
    const STATUS_BAYAR_BAYAR_SEBAGIAN = 2;
    const STATUS_BAYAR = [
        self::STATUS_BAYAR_BELUM_BAYAR,
        self::STATUS_BAYAR_LUNAS,
        self::STATUS_BAYAR_BAYAR_SEBAGIAN
    ];


    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }


    public function getStatusLabelAttribute()
    {
        return self::STATUS_BAYAR[$this->status];
    }

    public function getTanggalTagihanDibuatFormattedAttribute()
    {
        $date = Carbon::parse($this->tanggal_tagihan_dibuat);
        return $date->translatedFormat('F Y');
    }


    public function scopeActive($query)
    {
        return $query->where('is_deleted', false)->orWhere('is_deleted', null);
    }


    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_BAYAR_LUNAS);
    }

    public function scopePartialPaid($query)
    {
        return $query->where('status', self::STATUS_BAYAR_BAYAR_SEBAGIAN);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [self::STATUS_BAYAR_BELUM_BAYAR, self::STATUS_BAYAR_BAYAR_SEBAGIAN]);
    }

    public function scopeUnpaidOnly($query)
    {
        return $query->where('status', self::STATUS_BAYAR_BELUM_BAYAR);
    }
}
