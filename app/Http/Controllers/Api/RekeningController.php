<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekeningResource;
use App\Models\Rekening;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RekeningResource::collection(Rekening::orderBy('nama_rekening', 'asc')->get());
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
            'nama_pembayaran' => 'required',
            'nama_rekening' => 'required',
            'nomor_rekening' => 'required',
        ]);

        $data = Rekening::create($request->all());
        return new RekeningResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $area = Rekening::where('_id', $id)->first();
        if (!isset($area)) {
            throw new ModelNotFoundException();
        }
        return new RekeningResource($area);
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
        $request->validate([
            'nama_pembayaran' => 'required',
            'nama_rekening' => 'required',
            'nomor_rekening' => 'required',
        ]);


        $rekening = Rekening::where('_id', $id)->first();
        if (!isset($rekening)) {
            throw new ModelNotFoundException();
        }
        $rekening->update($request->all());
        return new RekeningResource($rekening);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Gabisa dihapus kalau ada rekening

        $rekening = Rekening::where('_id', $id)->first();
        if (!isset($rekening)) {
            throw new ModelNotFoundException();
        }
        $result = $rekening->delete();
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 500);
        }
    }
}
