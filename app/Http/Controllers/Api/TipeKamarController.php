<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TipeKamarResource;
use App\Models\TipeKamar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TipeKamarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tipeKamar = TipeKamar::orderBy('nama', 'asc');

        if ($request->has('area_id')) {
            $tipeKamar->where('area_id', $request->area_id);
        }

        return TipeKamarResource::collection($tipeKamar->paginate(10));
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
            'nama' => 'required',
            'kode' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',
        ]);

        $data = TipeKamar::create($request->all());
        return new TipeKamarResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipeKamar  $tipeKamar
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $tipeKamar = TipeKamar::find($id);
        if (!$tipeKamar) {
            throw new ModelNotFoundException();
        }

        return new TipeKamarResource($tipeKamar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipeKamar  $tipeKamar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String $id)
    {
        $tipeKamar = TipeKamar::find($id);
        if (!$tipeKamar) {
            throw new ModelNotFoundException();
        }

        $request->validate([
            'area_id' => ['required', 'exists:area_kos,_id'],
            'nama' => 'required',
            'kode' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',
        ]);

        $tipeKamar->update($request->all());
        return new TipeKamarResource($tipeKamar);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipeKamar  $tipeKamar
     * @return \Illuminate\Http\Response
     */
    public function destroy(String $id)
    {
        // TODO: Gabisa dihapus kalau ada kamar

        $tipeKamar = TipeKamar::find($id);
        if (!$tipeKamar) {
            throw new ModelNotFoundException();
        }
        $result = $tipeKamar->delete();
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 500);
        }
    }
}
