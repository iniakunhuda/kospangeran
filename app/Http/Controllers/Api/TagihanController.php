<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Base64Helper;
use App\Helpers\CarbonDateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RiwayatBayarResource;
use App\Http\Resources\Tagihan\TagihanResource;
use App\Http\Services\TagihanService;
use App\Models\AreaKos;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatBayar;
use App\Models\Tagihan;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $area_id = $request->area_id ?? '';
        $penyewa_id = $request->penyewa_id ?? '';
        $kamar_id = $request->kamar_id ?? '';
        $durasi = $request->durasi ?? '';

        $formatted = TagihanService::groupedInvoiceByUser(
            $request->status,
            $penyewa_id,
            $area_id,
            $kamar_id,
            $durasi
        );
        return response()->json(['data' => $formatted]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payInvoice(Request $request)
    {
        $request->validate([
            'penyewa_id' => ['required', 'exists:penyewa,_id'],
            'area_id' => ['required', 'exists:area_kos,_id'],
            'kamar_id' => ['required', 'exists:kamar,_id'],
            'rekening_id' => ['required', 'exists:rekening,_id'],
            'total_bayar' => 'required',
            'tanggal_bayar' => 'required',
            'durasi' => 'required',
            'deskripsi' => 'present',
            'bukti_pembayaran' => ['required', 'image64'],
        ]);

        $area = AreaKos::find($request->area_id);
        $penyewa = Penyewa::find($request->penyewa_id);
        $kamar = Kamar::with('tipe_kamar')->find($request->kamar_id);

        if (!$penyewa || !$kamar || !$area) {
            throw new ModelNotFoundException();
        }

        $request->merge([
            'tanggal_bayar' => CarbonDateHelper::formatTimestampToMongodate($request->tanggal_bayar),
            'penyewa' => $penyewa,
            'kamar' => $kamar,
            'area' => $area,
        ]);

        if ($request->exists('bukti_pembayaran')) {
            $filename = 'bukti_bayar_' . $request->penyewa_id . '_' . $request->kamar_id . '_' . uniqid();
            $path = RiwayatBayar::IMAGE_PATH;
            $result = Base64Helper::saveBase64Image($request->bukti_pembayaran, $filename, $path);
            $request->merge(['bukti_pembayaran' => $result]);
        }

        $tagihan_terbayar = TagihanService::payInvoice(
            $penyewa->_id,
            $area->_id,
            $kamar->_id,
            $request->durasi,
            $request->total_bayar
        );
        if (!$tagihan_terbayar) {
            throw new Exception('Tagihan gagal dibayar');
        }
        $request->merge(['detail_tagihan' => $tagihan_terbayar]);

        $riwayatBayar = RiwayatBayar::create($request->all());
        return new RiwayatBayarResource($riwayatBayar);
    }
}
