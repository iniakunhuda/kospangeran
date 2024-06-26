<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Base64Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PenyewaInvoiceResource;
use App\Http\Resources\PenyewaResource;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatBayar;
use App\Models\Sewa;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyewaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PenyewaResource::collection(Penyewa::orderBy('name', 'asc')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nomor_wa' => 'required',
            'tanggal_masuk' => 'required',
            'pekerjaan' => 'required',
            'deskripsi' => 'present',
            'foto_penyewa' => ['required', 'image64'],
            'foto_ktp' => ['required', 'image64']
        ]);
        $request->merge([
            'tanggal_bayar' => $request->tanggal_masuk
        ]);

        if ($request->exists('foto_ktp')) {
            $filename = $request->nama . '_ktp_' . uniqid();
            $path = Penyewa::IMAGE_PATH;
            $result = Base64Helper::saveBase64Image($request->foto_ktp, $filename, $path);

            $request->merge(['foto_ktp' => $result]);
        }

        if ($request->exists('foto_penyewa')) {
            $filename = $request->nama . '_profil_' . uniqid();
            $path = Penyewa::IMAGE_PATH;
            $result = Base64Helper::saveBase64Image($request->foto_penyewa, $filename, $path);

            $request->merge(['foto_penyewa' => $result]);
        }

        $data = Penyewa::create($request->all());
        return new PenyewaResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penyewa = Penyewa::find($id);
        if (!$penyewa) {
            throw new ModelNotFoundException();
        }

        return new PenyewaResource($penyewa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penyewa = Penyewa::find($id);
        if (!$penyewa) {
            throw new ModelNotFoundException();
        }

        $request->validate([
            'nama' => 'required',
            'nomor_wa' => 'required',
            'tanggal_masuk' => 'required',
            'pekerjaan' => 'required',
            'deskripsi' => 'present'
        ]);
        $request->merge([
            'tanggal_bayar' => $request->tanggal_masuk
        ]);


        if ($request->exists('foto_ktp')) {
            Storage::delete($request->foto_ktp);

            $filename = $request->nama . '_ktp_' . uniqid();
            $path = Penyewa::IMAGE_PATH;
            $result = Base64Helper::saveBase64Image($request->foto_ktp, $filename, $path);

            $request->merge(['foto_ktp' => $result]);
        }

        if ($request->exists('foto_penyewa')) {
            Storage::delete($request->foto_penyewa);

            $filename = $request->nama . '_profil_' . uniqid();
            $path = Penyewa::IMAGE_PATH;
            $result = Base64Helper::saveBase64Image($request->foto_penyewa, $filename, $path);

            $request->merge(['foto_penyewa' => $result]);
        }

        $penyewa->update($request->all());
        return new PenyewaResource($penyewa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $riwayat_sewa = Sewa::where('penyewa_id', $id)->first();
        $riwayat_bayar = RiwayatBayar::where('penyewa_id', $id)->first();
        if ($riwayat_sewa || $riwayat_bayar) {
            return response()->json(['message' => 'Tidak bisa menghapus penyewa karena sudah pernah menyewa'], 400);
        }


        $penyewa = Penyewa::find($id);
        if (!$penyewa) {
            throw new ModelNotFoundException();
        }

        Storage::delete($penyewa->foto_ktp);
        Storage::delete($penyewa->foto_penyewa);

        $result = $penyewa->delete();
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 500);
        }
    }
}
