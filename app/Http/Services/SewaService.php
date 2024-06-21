<?php

namespace App\Http\Services;

use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatPersewaan;
use App\Models\Sewa;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SewaService
{

    public static function disableAllSewa(Penyewa $penyewa)
    {
        $sewa = Sewa::active()->where('penyewa_id', $penyewa->_id);
        return $sewa->update(['is_active' => 0]);
    }

    public static function updateSewaPriceWhenKamarUpdatted(Kamar $kamar, int $newPrice, String $duration)
    {
        $sewa = Sewa::active()->where('kamar_id', $kamar->_id);
        return $sewa->update(['total_bayar' => $newPrice, 'durasi' => $duration]);
    }
}
