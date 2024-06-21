<?php

namespace App\Http\Services;

use App\Models\AreaKos;
use App\Models\Kamar;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AreaService
{

    public static function statistikPendapatan($durasi)
    {
        $response = [];
        $areas = AreaKos::get();

        foreach ($areas as $area) {
            foreach ($area->tipe_kamar as $tipe) {
                $response[] = [
                    'area' => $area->judul,
                    'area_id' => $area->id,
                    'tipe' => $tipe->nama,
                    'jumlah' => $tipe->kamar->where('durasi', $durasi)->count(),
                    'kosong' => $tipe->kamar->where('durasi', $durasi)->where('status', Kamar::STATUS_KOSONG)->count(),
                    'terisi' => $tipe->kamar->where('durasi', $durasi)->where('status', Kamar::STATUS_TERISI)->count(),
                    'pendapatan' => $tipe->kamar->where('durasi', $durasi)->where('status', Kamar::STATUS_TERISI)->sum('harga'),
                ];
            }
        }

        return $response;
    }
}
