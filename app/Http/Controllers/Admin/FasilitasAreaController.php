<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaKos;
use Illuminate\Http\Request;

class FasilitasAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AreaKos $area_id)
    {
        return view('admin.area.fasilitas_area.create', [
            'area' => $area_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AreaKos $area_id)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required',
            'catatan' => 'required',
        ]);
        $request['id'] = (string) new \MongoDB\BSON\ObjectID();

        $area = $area_id;
        $result = $area->push('fasilitas', $request->all());

        if ($result) {
            return redirect()->route('area.show', $area->id)->with('success', 'Data fasilitas berhasil disimpan');
        } else {
            return redirect()->route('area.show', $area->id)->withErrors(['error' => 'Data fasilitas gagal disimpan']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AreaKos $area, $id)
    {
        $fasilitas = collect($area->fasilitas)->where('id', $id)->first();
        if (!$fasilitas) {
            return abort(404, 'Fasilitas Area Tidak Ditemukan');
        }

        return view('admin.area.fasilitas_area.edit', [
            'area' => $area,
            'fasilitas' => $fasilitas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $area_id, $fasilitas_id)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required',
            'catatan' => 'required',
        ]);
        $request['id'] = $fasilitas_id;

        $area = AreaKos::find($area_id);
        if (!$area) {
            return abort(404, 'Area Kos Tidak Ditemukan');
        }

        $fasilitas = collect($area->fasilitas)->where('id', $fasilitas_id)->first();
        if (!$fasilitas) {
            return abort(404, 'Fasilitas Area Tidak Ditemukan');
        }

        $area->pull('fasilitas', ['id' => $fasilitas_id]);
        $result = $area->push('fasilitas', $request->only('nama', 'jumlah', 'catatan', 'id'));

        if ($result) {
            return redirect()->route('area.show', $area_id)->with('success', 'Data fasilitas berhasil diperbarui');
        } else {
            return redirect()->route('area.show', $area_id)->withErrors(['error' => 'Data fasilitas gagal diperbarui']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($area_id, $fasilitas_id)
    {
        $area = AreaKos::find($area_id);
        if (!$area) {
            return abort(404, 'Area Kos Tidak Ditemukan');
        }

        $fasilitas = collect($area->fasilitas)->where('id', $fasilitas_id)->first();
        if (!$fasilitas) {
            return abort(404, 'Fasilitas Area Tidak Ditemukan');
        }

        $result = $area->pull('fasilitas', ['id' => $fasilitas_id]);

        if ($result) {
            return redirect()->route('area.show', $area_id)->with('success', 'Data fasilitas berhasil dihapus');
        } else {
            return redirect()->route('area.show', $area_id)->withErrors(['error' => 'Data fasilitas gagal dihapus']);
        }
    }
}
