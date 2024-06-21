<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Mongodb\Eloquent\Model;

class RiwayatBayar extends Model
{
    protected $collection = 'riwayat_bayar';
    protected $fillable = ['area_id', 'kamar_id', 'penyewa_id', 'rekening_id', 'tanggal_bayar', 'total_bayar', 'durasi', 'status', 'detail_tagihan', 'bukti_pembayaran'];
    protected $dates = ['created_at', 'updated_at', 'tanggal_bayar'];

    const IMAGE_PATH = 'foto_bukti_bayar';

    public function getBuktiPembayaranUrlAttribute()
    {
        if (!empty($this->bukti_pembayaran)) {
            return url(Storage::url($this->bukti_pembayaran));
        }
        return "";
    }

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

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
