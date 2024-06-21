<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class RiwayatKamar extends Model
{
    protected $collection = 'riwayat_kamar';
    protected $fillable = ['area_id', 'kamar_id', 'penyewa_id', 'tanggal', 'deskripsi', 'kategori', 'catatan_berhenti', 'tanggal_berhenti'];
    protected $dates = ['created_at', 'updated_at', 'tanggal', 'tanggal_berhenti'];

    const KATEGORI_RIWAYAT_TRANSAKSI = "Transaksi";
    const KATEGORI_RIWAYAT_RENOVASI = "Renovasi";
    const KATEGORI_RIWAYAT_PINDAH = "Pindah";
    const KATEGORI_RIWAYAT_BERHENTI_SEWA = "Berhenti Sewa";
    const KATEGORI_RIWAYAT = [
        self::KATEGORI_RIWAYAT_TRANSAKSI,
        self::KATEGORI_RIWAYAT_RENOVASI,
        self::KATEGORI_RIWAYAT_PINDAH,
        self::KATEGORI_RIWAYAT_BERHENTI_SEWA
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function area()
    {
        return $this->belongsTo(AreaKos::class);
    }

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }
}
