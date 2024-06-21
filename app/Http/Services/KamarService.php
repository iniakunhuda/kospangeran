<?php

namespace App\Http\Services;

use App\Models\Kamar;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KamarService
{

    public function getKamarByAreaId($area_id)
    {
        return Kamar::where('area_id', $area_id)->get();
    }

    public function getKamarById($kamar_id)
    {
        return Kamar::where('_id', $kamar_id)->first();
    }

    public static function assignPenyewa($kamar_id, $penyewa_id)
    {
        $kamar = Kamar::where('_id', $kamar_id)->first();
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }
        $kamar->penyewa_id = $penyewa_id;
        $kamar->status = Kamar::STATUS_TERISI;
        return $kamar->save();
    }

    public static function removePenyewa($kamar_id, $penyewa_id)
    {
        $kamar = Kamar::where('_id', $kamar_id)->first();
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }
        $kamar->penyewa_id = null;
        $kamar->status = Kamar::STATUS_KOSONG;
        return $kamar->save();
    }
}
