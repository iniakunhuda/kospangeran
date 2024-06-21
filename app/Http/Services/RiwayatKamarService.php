<?php

namespace App\Http\Services;

use App\Helpers\CarbonDateHelper;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatKamar;
use App\Models\RiwayatPersewaan;
use App\Models\Sewa;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RiwayatKamarService
{

    public static function createHistory(Sewa $sewa, String $kategori, String $deskripsi)
    {
        $kamar = Kamar::find($sewa->kamar_id);
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }

        $penyewa = Penyewa::find($sewa->penyewa_id);
        if (!$penyewa) {
            throw new \Exception('Penyewa tidak ditemukan');
        }

        return RiwayatKamar::create([
            'area_id' => $kamar->area_id,
            'kamar_id' => $sewa->kamar_id,
            'penyewa_id' => $sewa->penyewa_id,
            'tanggal' => Carbon::now(),
            'deskripsi' => $deskripsi,
            'kategori' => $kategori,
        ]);
    }

    public static function createHistoryRenovation(Kamar $kamar, String $deskripsi, String $tanggal)
    {
        $penyewa_id = null;
        $sewa = Sewa::active()->where('kamar_id', $kamar->_id)->first();
        if ($sewa) {
            $penyewa_id = $sewa->penyewa_id;
        }

        return RiwayatKamar::create([
            'area_id' => $kamar->area_id,
            'kamar_id' => $kamar->_id,
            'penyewa_id' => $penyewa_id,
            'tanggal' => CarbonDateHelper::formatTimestampToMongodate($tanggal),
            'deskripsi' => $deskripsi,
            'kategori' => RiwayatKamar::KATEGORI_RIWAYAT_RENOVASI,
        ]);
    }

    public static function createHistoryStopRent(Sewa $sewa, String $kategori, String $deskripsi, String $catatan, String $tanggal_berhenti)
    {
        $kamar = Kamar::find($sewa->kamar_id);
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }

        $penyewa = Penyewa::find($sewa->penyewa_id);
        if (!$penyewa) {
            throw new \Exception('Penyewa tidak ditemukan');
        }

        return RiwayatKamar::create([
            'area_id' => $kamar->area_id,
            'kamar_id' => $sewa->kamar_id,
            'penyewa_id' => $sewa->penyewa_id,
            'tanggal' => Carbon::now(),
            'deskripsi' => $deskripsi,
            'kategori' => $kategori,
            'catatan_berhenti' => $catatan,
            'tanggal_berhenti' => CarbonDateHelper::formatTimestampToMongodate($tanggal_berhenti),
        ]);
    }
}
