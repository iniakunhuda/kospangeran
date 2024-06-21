<?php

namespace App\Http\Controllers\Api;

use App\Events\SewaKamarEvent;
use App\Helpers\CarbonDateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\KamarResource;
use App\Http\Resources\SewaResource;
use App\Http\Services\KamarService;
use App\Http\Services\RiwayatKamarService;
use App\Http\Services\SewaService;
use App\Http\Services\TagihanService;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatKamar;
use App\Models\Sewa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SewaController extends Controller
{

    // TODO: Perlu dijelaskan ke pemilik, kalau ada orang yg pindah kamar, tagihan harus diremove manual

    public function rentNewRoom(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'kamar_id' => 'required',
            'penyewa_id' => 'required',
            'tanggal_bayar' => 'required',
            'total_bayar' => ['required', 'int'],
            'durasi' => 'required',
            'catatan' => 'present'
        ]);

        if (!in_array($request->durasi, Sewa::DURASI_SEWA)) {
            throw new \Exception('Durasi sewa tidak valid');
        }

        $penyewa = Penyewa::find($request->penyewa_id);
        if (!$penyewa) {
            throw new \Exception('Penyewa tidak ditemukan');
        }

        $kamar = Kamar::find($request->kamar_id);
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }

        // cek kamar sudah ada penyewanya belum
        if ($kamar->status == Kamar::STATUS_TERISI && $kamar->penyewa_id != $request->penyewa_id) {
            throw new \Exception('Kamar sudah terisi oleh penyewa lain');
        }
        if ($kamar->status == Kamar::STATUS_TERISI && $kamar->penyewa_id == $request->penyewa_id) {
            throw new \Exception('Kamar sudah diisi oleh penyewa ini');
        }

        // cek penyewa sudah sewa kamar lain atau belum
        $isSewaActive = Sewa::active()->where('penyewa_id', $request->penyewa_id)->first();
        if ($isSewaActive) {
            throw new \Exception('Penyewa sudah sewa kamar lain. Silahkan pilih menu pindah kamar');
        }

        $request->merge([
            'area_id' => $kamar->area_id,
            'is_active' => 1,
            'tanggal_bayar' => CarbonDateHelper::formatTimestampToMongodate($request->tanggal_bayar),
        ]);

        $sewa = Sewa::create($request->all());
        TagihanService::createNewInvoice($sewa);
        KamarService::assignPenyewa($request->kamar_id, $request->penyewa_id);
        RiwayatKamarService::createHistory($sewa, RiwayatKamar::KATEGORI_RIWAYAT_TRANSAKSI, "Disewakan ke $penyewa->nama");

        return new SewaResource($sewa);
    }

    public function rentMoveRoom(Request $request)
    {
        $request->validate([
            'kamar_id_lama' => 'required',
            'area_id' => 'required',
            'kamar_id' => 'required',
            'penyewa_id' => 'required',
            'durasi' => 'required',
            'tanggal_bayar' => 'required',
            'total_bayar' => ['required', 'int'],
            'catatan' => 'present'
        ]);

        if (!in_array($request->durasi, Sewa::DURASI_SEWA)) {
            throw new \Exception('Durasi sewa tidak valid');
        }

        $penyewa = Penyewa::find($request->penyewa_id);
        if (!$penyewa) {
            throw new \Exception('Penyewa tidak ditemukan');
        }

        $kamar = Kamar::find($request->kamar_id);
        $kamar_lama = Kamar::find($request->kamar_id_lama);
        if (!$kamar || !$kamar_lama) {
            throw new \Exception('Kamar tidak ditemukan');
        }

        // cek kamar old bukan user yg nempatin
        if ($kamar_lama->penyewa_id != $request->penyewa_id) {
            throw new \Exception("$kamar_lama->nama tidak disewa oleh $penyewa->nama");
        }

        // cek kamar lama harus beda dgn pindah kamar baru
        if ($kamar_lama->_id == $kamar->_id) {
            throw new \Exception('Kamar lama dan kamar baru harus beda');
        }

        // cek kamar sudah ada penyewanya belum
        if ($kamar->status == Kamar::STATUS_TERISI) {
            throw new \Exception('Kamar baru sudah terisi oleh penyewa lain');
        }

        $request->merge([
            'area_id' => $kamar->area_id,
            'is_active' => 1,
            'tanggal_bayar' => CarbonDateHelper::formatTimestampToMongodate($request->tanggal_bayar),
        ]);

        SewaService::disableAllSewa($penyewa);

        $sewa = Sewa::create($request->all());
        TagihanService::createNewInvoice($sewa);
        KamarService::removePenyewa($request->kamar_id_lama, $request->penyewa_id);
        KamarService::assignPenyewa($request->kamar_id, $request->penyewa_id);
        RiwayatKamarService::createHistory(
            $sewa,
            RiwayatKamar::KATEGORI_RIWAYAT_PINDAH,
            "$penyewa->nama pindah dari $kamar_lama->nama ke $kamar->nama"
        );

        return new SewaResource($sewa);
    }


    public function stopRentRoom(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'kamar_id' => 'required',
            'penyewa_id' => 'required',
            'tanggal_berhenti' => 'required',
            'catatan' => 'required'
        ]);

        $penyewa = Penyewa::find($request->penyewa_id);
        if (!$penyewa) {
            throw new \Exception('Penyewa tidak ditemukan');
        }

        $kamar = Kamar::find($request->kamar_id);
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }
        if ($kamar->status == Kamar::STATUS_KOSONG) {
            throw new \Exception('Kamar belum disewakan ke penyewa');
        }

        // cek bukan penyewa kamar
        $isPenyewaKamar = $kamar->penyewa_id == $request->penyewa_id;
        if (!$isPenyewaKamar) {
            throw new \Exception("Kamar bukan milik $penyewa->nama");
        }

        // cek penyewa belum sewa apapun
        $sewa = Sewa::active()->where('penyewa_id', $request->penyewa_id)->first();
        if (!$sewa) {
            throw new \Exception("$penyewa->nama belum menyewa kamar");
        }

        SewaService::disableAllSewa($penyewa);
        KamarService::removePenyewa($request->kamar_id, $request->penyewa_id);
        RiwayatKamarService::createHistoryStopRent(
            $sewa,
            RiwayatKamar::KATEGORI_RIWAYAT_BERHENTI_SEWA,
            "$penyewa->nama berhenti sewa dari $kamar->nama",
            $request->catatan,
            $request->tanggal_berhenti
        );
        return new SewaResource($sewa);
    }
}
