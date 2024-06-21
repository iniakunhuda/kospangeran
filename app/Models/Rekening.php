<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Rekening extends Model
{
    protected $collection = 'rekening';
    protected $fillable = ['nama_pembayaran', 'nama_rekening', 'nomor_rekening'];
    protected $dates = ['created_at', 'updated_at'];

    public function getIsAllowDeleteAttribute()
    {
        return $this->riwayat->count() == 0;
    }


    public function riwayat()
    {
        return $this->hasMany(RiwayatBayar::class, 'rekening_id', '_id');
    }
}
