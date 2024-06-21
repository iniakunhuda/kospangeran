<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Mongodb\Eloquent\Model;

class Penyewa extends Model
{
    protected $collection = 'penyewa';

    protected $fillable = ['nama', 'nomor_wa', 'tanggal_masuk', 'tanggal_bayar', 'pekerjaan', 'deskripsi', 'foto_penyewa', 'foto_ktp'];

    protected $hidden = ['foto_ktp', 'foto_penyewa'];
    protected $appends = ['foto_ktp_url', 'foto_penyewa_url'];

    const IMAGE_PATH = 'foto_penyewa';

    public function getFormatTanggalMasukAttribute()
    {
        return Carbon::parse($this->tanggal_masuk)->format('d M Y');
    }

    public function getFormatTanggalBayarAttribute()
    {
        return Carbon::parse($this->tanggal_bayar)->format('d M Y');
    }

    public function getFotoKtpUrlAttribute()
    {
        if (!empty($this->foto_ktp)) {
            return url(Storage::url($this->foto_ktp));
        }
        return "";
    }

    public function getLinkWaAttribute()
    {
        return "https://wa.me/" . $this->nomor_wa;
    }

    public function getFotoPenyewaUrlAttribute()
    {
        if (!empty($this->foto_penyewa)) {
            return url(Storage::url($this->foto_penyewa));
        }
        return "";
    }

    public function getIsAllowDeleteAttribute()
    {
        return ($this->kamar == null) && ($this->tagihan->count() == 0);
    }

    public function getSewaAttribute()
    {
        return Sewa::active()->where('penyewa_id', $this->_id)->with(['kamar', 'area'])->first();
    }

    public function kamar()
    {
        return $this->hasOne(Kamar::class, 'penyewa_id', '_id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'penyewa_id', '_id');
    }
}
