<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AreaKosDataTable;
use App\Http\Controllers\Controller;
use App\Http\Resources\AreaDetailResource;
use App\Models\AreaKos;
use App\Models\Kamar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataTable = AreaKos::orderBy('judul', 'asc')->get();
        return view('admin.area.index', [
            'dataTable' => $dataTable
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.area.create');
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

        $result = AreaKos::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis,
            'alamat' => $request->alamat,
        ]);

        if ($result) {
            return redirect()->route('area.index')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('area.index')->withErrors(['error' => 'Data gagal disimpan']);
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
        Session::put('AREA_ID', $id);

        $area = AreaKos::with(['tipe_kamar'])->where('_id', $id)->first();
        if (!$area) {
            return abort(404, 'Area Kos Tidak Ditemukan');
        }

        return view('admin.area.show', [
            'area' => $area,
            'dataTable_tipe_kamar' => $area->tipe_kamar,
            'dataTable_fasilitas' => collect(
                $area->fasilitas
            )->sortBy('nama'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $area = AreaKos::find($id);
        if (!$area) {
            return abort(404, 'Area Kos Tidak Ditemukan');
        }
        return view('admin.area.edit', [
            'area' => $area
        ]);
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
            'judul' => 'required',
            'deskripsi' => 'required',
            'jenis' => 'required',
            'alamat' => 'required',
        ]);

        $area = AreaKos::find($id);
        $result = $area->update($request->all());
        if ($result) {
            return redirect()->route('area.index')->with('success', 'Data berhasil diperbarui');
        } else {
            return redirect()->route('area.index')->withErrors(['error' => 'Data gagal diperbarui']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = AreaKos::find($id);
        if (!$area) {
            return redirect()->back()->withErrors(['error' => 'Data tidak ditemukan']);
        }

        if (!$area->is_allow_delete) {
            return $this->getRedirectRoute()->withErrors(['error' => 'Area tidak dapat dihapus karena sudah ada kamar']);
        }

        $result = $area->delete();
        if ($result) {
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->withErrors(['error' => 'Data gagal dihapus']);
        }
    }


    public function apiFetch(Request $request)
    {
        $query = AreaKos::orderBy('judul', 'asc');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $response = [];
        $area = $query->get();
        foreach ($area as $ar) {
            $response[] = [
                'id' => $ar->_id,
                'text' => $ar->judul,
            ];
        }
        return response()->json($response);
    }
}
