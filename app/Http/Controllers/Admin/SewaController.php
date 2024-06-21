<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CarbonDateHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\KamarService;
use App\Http\Services\RiwayatKamarService;
use App\Http\Services\SewaService;
use App\Http\Services\TagihanService;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatKamar;
use App\Models\Sewa;
use Illuminate\Http\Request;

class SewaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSewaBaru()
    {
        return view('admin.sewa.sewa_baru.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSewaBaru(Request $request)
    {
        $request->validate([
            'area_id' => 'required',
            'kamar_id' => 'required',
            'penyewa_id' => 'required',
            'tanggal_bayar' => 'required',
            'total_bayar' => 'required',
            'durasi' => 'required',
            'catatan' => 'present'
        ]);

        if (!in_array($request->durasi, Sewa::DURASI_SEWA)) {
            return redirect()->back()->withErrors(['error' => 'Durasi sewa tidak valid']);
        }

        $penyewa = Penyewa::find($request->penyewa_id);
        if (!$penyewa) {
            return redirect()->back()->withErrors(['error' => 'Penyewa tidak ditemukan']);
        }

        $kamar = Kamar::find($request->kamar_id);
        if (!$kamar) {
            return redirect()->back()->withErrors(['error' => 'Kamar tidak ditemukan']);
        }

        // cek kamar sudah ada penyewanya belum
        if ($kamar->status == Kamar::STATUS_TERISI && $kamar->penyewa_id != $request->penyewa_id) {
            return redirect()->back()->withErrors(['error' => 'Kamar sudah terisi oleh penyewa lain']);
        }
        if ($kamar->status == Kamar::STATUS_TERISI && $kamar->penyewa_id == $request->penyewa_id) {
            return redirect()->back()->withErrors(['error' => 'Kamar sudah diisi oleh penyewa ini']);
        }

        // cek penyewa sudah sewa kamar lain atau belum
        $isSewaActive = Sewa::active()->where('penyewa_id', $request->penyewa_id)->first();
        if ($isSewaActive) {
            return redirect()->back()->withErrors(['error' => 'Penyewa sudah sewa kamar lain. Silahkan pilih menu pindah kamar']);
        }

        $request->merge([
            'area_id' => $kamar->area_id,
            'is_active' => 1,
            'tanggal_bayar' => CarbonDateHelper::formatDateStringToMongodate($request->tanggal_bayar),
        ]);


        $sewa = Sewa::create($request->all());
        TagihanService::createNewInvoice($sewa);
        KamarService::assignPenyewa($request->kamar_id, $request->penyewa_id);
        RiwayatKamarService::createHistory($sewa, RiwayatKamar::KATEGORI_RIWAYAT_TRANSAKSI, "Disewakan ke $penyewa->nama");
        if ($sewa) {
            return redirect()->back()->with('success', 'Berhasil menambah sewa baru');
        } else {
            return redirect()->back()->withErrors(['error' => 'Gagal menambah sewa baru']);
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSewaPindah($old_sewa_id)
    {
        $data['sewa'] = Sewa::find($old_sewa_id);
        if (!$data['sewa']) {
            return abort(404, 'Sewa tidak ditemukan');
        }

        return view('admin.sewa.sewa_pindah.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSewaPindah(Request $request, $old_sewa_id)
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
            return redirect()->back()->withErrors(['error' => 'Durasi sewa tidak valid']);
        }

        $penyewa = Penyewa::find($request->penyewa_id);
        if (!$penyewa) {
            return redirect()->back()->withErrors(['error' => 'Penyewa tidak ditemukan']);
        }

        $kamar = Kamar::find($request->kamar_id);
        $kamar_lama = Kamar::find($request->kamar_id_lama);
        if (!$kamar || !$kamar_lama) {
            return redirect()->back()->withErrors(['error' => 'Kamar tidak ditemukan']);
        }

        // cek kamar old bukan user yg nempatin
        if ($kamar_lama->penyewa_id != $request->penyewa_id) {
            return redirect()->back()->withErrors(['error' => "$kamar_lama->nama tidak disewa oleh $penyewa->nama"]);
        }

        // cek kamar lama harus beda dgn pindah kamar baru
        if ($kamar_lama->_id == $kamar->_id) {
            return redirect()->back()->withErrors(['error' => 'Kamar lama dan kamar baru harus beda']);
        }

        // cek kamar sudah ada penyewanya belum
        if ($kamar->status == Kamar::STATUS_TERISI) {
            return redirect()->back()->withErrors(['error' => 'Kamar baru sudah terisi oleh penyewa lain']);
        }

        $request->merge([
            'area_id' => $kamar->area_id,
            'is_active' => 1,
            'tanggal_bayar' => CarbonDateHelper::formatDateStringToMongodate($request->tanggal_bayar),
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
        if ($sewa) {
            return redirect()->back()->with('success', 'Berhasil pindah kamar');
        } else {
            return redirect()->back()->withErrors(['error' => 'Gagal pindah kamar']);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSewaBerhenti($old_sewa_id)
    {
        $data['sewa'] = Sewa::find($old_sewa_id);
        if (!$data['sewa']) {
            return abort(404, 'Sewa tidak ditemukan');
        }

        return view('admin.sewa.sewa_berhenti.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSewaBerhenti(Request $request, $old_sewa_id)
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
            return redirect()->back()->withErrors(['error' => 'Penyewa tidak ditemukan']);
        }

        $kamar = Kamar::find($request->kamar_id);
        if (!$kamar) {
            return redirect()->back()->withErrors(['error' => 'Kamar tidak ditemukan']);
        }
        if ($kamar->status == Kamar::STATUS_KOSONG) {
            return redirect()->back()->withErrors(['error' => 'Kamar belum disewakan ke penyewa']);
        }

        // cek bukan penyewa kamar
        $isPenyewaKamar = $kamar->penyewa_id == $request->penyewa_id;
        if (!$isPenyewaKamar) {
            return redirect()->back()->withErrors(['error' => "Kamar bukan milik $penyewa->nama"]);
        }

        // cek penyewa belum sewa apapun
        $sewa = Sewa::active()->where('penyewa_id', $request->penyewa_id)->first();
        if (!$sewa) {
            return redirect()->back()->withErrors(['error' => "$penyewa->nama belum menyewa kamar"]);
        }

        $request->merge([
            'tanggal_berhenti' => CarbonDateHelper::formatDateStringToMongodate($request->tanggal_berhenti),
        ]);

        $result = SewaService::disableAllSewa($penyewa);
        KamarService::removePenyewa($request->kamar_id, $request->penyewa_id);
        RiwayatKamarService::createHistoryStopRent(
            $sewa,
            RiwayatKamar::KATEGORI_RIWAYAT_BERHENTI_SEWA,
            "$penyewa->nama berhenti sewa dari $kamar->nama",
            $request->catatan,
            $request->tanggal_berhenti
        );
        if ($result) {
            return redirect()->back()->with('success', 'Berhasil berhenti sewa');
        } else {
            return redirect()->back()->withErrors(['error' => 'Gagal berhenti sewa']);
        }
    }
}
