<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Base64Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\KamarResource;
use App\Http\Services\RiwayatKamarService;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatKamar;
use App\Models\Sewa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kamar = Kamar::orderBy('status', 'asc')->orderBy('nama', 'asc');

        if ($request->has('area_id')) {
            $kamar->where('area_id', $request->area_id);
        }

        if ($request->has('is_kamar_kosong')) {
            $kamar->where('status', Kamar::STATUS_KOSONG);
        }

        return KamarResource::collection($kamar->paginate(10));
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
            'area_id' => ['required', 'exists:area_kos,_id'],
            'tipe_kamar_id' => ['required', 'exists:tipe_kamar,_id'],
            'nama' => 'required',
            'nomor' => 'required',
            'lantai' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',
            'deskripsi' => 'present',
            'status' => 'required'
        ]);

        if (!in_array($request->status, Kamar::STATUS)) {
            throw new \Exception('Status Kamar Tidak Valid');
        }

        // upload foto
        $fotos = [];
        if ($request->exists('foto') && (count($request->foto) > 0)) {
            $request->validate([
                'foto.*' => 'image64'
            ]);

            foreach ($request->foto as $key => $foto) {
                $filename = $request->nama . '_' . $key . '_' . uniqid();
                $path = Kamar::IMAGE_PATH;
                $result = Base64Helper::saveBase64Image($foto, $filename, $path);
                $fotos[] = $result;
            }

            $request->merge(['foto' => $fotos, 'penyewa' => null]);
        }

        $data = Kamar::create($request->all());
        return new KamarResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kamar = Kamar::find($id);
        if (!$kamar) {
            throw new ModelNotFoundException();
        }

        return new KamarResource($kamar);
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
        $kamar = Kamar::find($id);
        if (!$kamar) {
            throw new ModelNotFoundException();
        }

        $request->validate([
            'area_id' => ['required', 'exists:area_kos,_id'],
            'tipe_kamar_id' => ['required', 'exists:tipe_kamar,_id'],
            'nama' => 'required',
            'nomor' => 'required',
            'lantai' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',
            'deskripsi' => 'present',
            'status' => 'required'
        ]);

        if (!in_array($request->status, Kamar::STATUS)) {
            throw new \Exception('Status Kamar Tidak Valid');
        }

        // upload foto
        $fotos = [];
        if ($request->exists('foto') && (count($request->foto) > 0)) {
            $request->validate([
                'foto.*' => 'image64'
            ]);

            // remove old photos
            $oldPhotos = $kamar->foto;
            foreach ($oldPhotos as $oldPhoto) {
                Storage::delete($oldPhoto);
            }

            foreach ($request->foto as $key => $foto) {
                $filename = $request->nama . '_' . $key . '_' . uniqid();
                $path = Kamar::IMAGE_PATH;
                $result = Base64Helper::saveBase64Image($foto, $filename, $path);
                $fotos[] = $result;
            }

            $request->merge(['foto' => $fotos]);
            $kamar->update($request->all());
        } else {
            $kamar->update($request->except('foto'));
        }

        return new KamarResource($kamar);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Gabisa dihapus kalau ada orang

        $kamar = Kamar::find($id);
        if (!$kamar) {
            throw new ModelNotFoundException();
        }

        // remove old photos
        if ((count($kamar->foto) > 0)) {
            $oldPhotos = $kamar->foto;
            foreach ($oldPhotos as $oldPhoto) {
                Storage::delete($oldPhoto);
            }
        }

        $result = $kamar->delete();
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRenovation(String $id)
    {
        $kamar = Kamar::where('_id', $id)->with('riwayat');
        if (!$kamar) {
            throw new ModelNotFoundException();
        }

        return KamarResource::collection($kamar->paginate(10));
    }


    /**
     * Perbaikan kamar
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeRenovation(Request $request, String $id)
    {
        $request->validate([
            'tanggal' => 'required',
            'catatan' => 'required',
        ]);

        $kamar = Kamar::find($id);
        if (!$kamar) {
            throw new ModelNotFoundException();
        }

        RiwayatKamarService::createHistoryRenovation($kamar, $request->catatan, $request->tanggal);
        return new KamarResource($kamar);
    }


    public function historyPenyewa(String $id)
    {
        $kamar = Kamar::where('_id', $id);
        if (!$kamar) {
            throw new ModelNotFoundException();
        }

        $riwayat = Sewa::raw(function ($collection) use ($id) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'kamar_id' => $id,
                    ],
                ],
                [
                    '$group' => [
                        '_id' => [
                            'penyewa_id' => '$penyewa_id',
                            'year' => ['$year' => '$tanggal_bayar'],
                            'month' => ['$month' => '$tanggal_bayar']
                        ],
                        'total_bayar' => ['$sum' => '$total_bayar']
                    ]
                ],
                [
                    '$project' => [
                        '_id' => '$_id.penyewa_id',
                        'year' => '$_id.year',
                        'month' => '$_id.month',
                        'total_bayar' => '$total_bayar'
                    ]
                ]
            ]);
        });

        $riwayat->each(function ($item) {
            $item->penyewa = Penyewa::find($item->_id);
        });

        return response()->json($riwayat);
    }
}
