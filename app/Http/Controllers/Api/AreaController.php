<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaDetailResource;
use App\Http\Resources\AreaResource;
use App\Models\AreaKos;
use App\Models\TipeKamar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AreaResource::collection(AreaKos::orderBy('judul', 'asc')->paginate(10));
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
            'judul' => 'required',
            'deskripsi' => 'required',
            'jenis' => 'required',
            'alamat' => 'required',
        ]);

        $data = AreaKos::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis,
            'alamat' => $request->alamat,
        ]);
        return new AreaResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $area = AreaKos::with('tipe_kamar')->where('_id', $id)->first();
        if (!isset($area)) {
            throw new ModelNotFoundException();
        }
        return new AreaDetailResource($area);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AreaKos $area)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'jenis' => 'required',
            'alamat' => 'required',
        ]);

        $area->update($request->all());
        return new AreaResource($area);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AreaKos $area)
    {
        $tipe_kamar = TipeKamar::where('area_id', $area->_id)->get();
        if (count($tipe_kamar) > 0) {
            return response()->json(['message' => 'Area tidak bisa dihapus karena masih memiliki tipe kamar'], 400);
        }

        $result = $area->delete();
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFasilitas(Request $request, String $area_id)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required',
            'catatan' => 'required',
        ]);
        $request['id'] = (string) new \MongoDB\BSON\ObjectID();

        $area = AreaKos::where('_id', $area_id)->first();
        if (!isset($area)) {
            throw new ModelNotFoundException();
        }

        $area->push('fasilitas', $request->all());

        $area = AreaKos::where('_id', $area_id)->first();
        return new AreaResource($area);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFasilitas(Request $request, String $area_id, String $id)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required',
            'catatan' => 'required',
        ]);
        $request['id'] = $id;

        $area = AreaKos::where('_id', $area_id)->first();
        if (!isset($area)) {
            throw new ModelNotFoundException();
        }

        $area->pull('fasilitas', ['id' => $id]);
        $area->push('fasilitas', $request->all());

        $area = AreaKos::where('_id', $area_id)->first();
        return new AreaResource($area);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyFasilitas(String $area_id, String $id)
    {
        $area = AreaKos::where('_id', $area_id)->first();
        if (!isset($area)) {
            throw new ModelNotFoundException();
        }

        $result = $area->pull('fasilitas', ['_id' => $id]);
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 500);
        }
    }
}
