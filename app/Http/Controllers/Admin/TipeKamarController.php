<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaKos;
use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TipeKamarController extends Controller
{

    private function getRedirectRoute()
    {
        if (session('AREA_ID') && session('AREA_ID') != 'all') {
            $route = route('area.show', session('AREA_ID'));
        } else {
            $route = route('tipe_kamar.index');
        }
        return redirect($route);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Session::remove('AREA_ID');

        $tipe_kamar = TipeKamar::orderBy('nama', 'asc');
        if ($request->filled('area_id')) {
            $tipe_kamar = $tipe_kamar->where('area_id', $request->area_id);
        }

        $filter_area = AreaKos::orderBy('judul', 'asc')->get();

        return view('admin.tipe_kamar.index', [
            'dataTable_tipe_kamar' => $tipe_kamar->get(),
            'filter_areas' => $filter_area
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['areas'] = AreaKos::orderBy('judul', 'asc')->get();
        return view('admin.tipe_kamar.create', $data);
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

        $result = TipeKamar::create($request->all());
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Tipe Kamar berhasil disimpan');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Tipe Kamar gagal disimpan']);
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
        $data['tipe_kamar'] = TipeKamar::find($id);

        $dataTable_kamar = Kamar::where('tipe_kamar_id', $id)->orderBy('status', 'asc')->orderBy('nama', 'asc');
        $data['dataTable_kamar'] = $dataTable_kamar->get();

        if (!$data['tipe_kamar']) {
            return abort(404, 'Tipe Kamar tidak ditemukan');
        }
        return view('admin.tipe_kamar.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['areas'] = AreaKos::orderBy('judul', 'asc')->get();
        $data['tipe_kamar'] = TipeKamar::find($id);
        if (!$data['tipe_kamar']) {
            return abort(404, 'Tipe Kamar tidak ditemukan');
        }

        return view('admin.tipe_kamar.edit', $data);
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
        $tipeKamar = TipeKamar::find($id);
        if (!$tipeKamar) {
            return abort(404, 'Tipe Kamar tidak ditemukan');
        }

        $request->validate([
            'area_id' => ['required', 'exists:area_kos,_id'],
            'nama' => 'required',
            'kode' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',
        ]);

        $result = $tipeKamar->update($request->all());
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Tipe Kamar berhasil diperbarui');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Tipe Kamar gagal diperbarui']);
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
        $tipe_kamar = TipeKamar::find($id);
        if (!$tipe_kamar) {
            return $this->getRedirectRoute()->withErrors(['error' => 'Tipe Kamar tidak ditemukan']);
        }

        if (!$tipe_kamar->is_allow_delete) {
            return $this->getRedirectRoute()->withErrors(['error' => 'Tipe Kamar tidak dapat dihapus karena sudah ada kamar']);
        }

        $result = $tipe_kamar->delete();
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Tipe Kamar berhasil dihapus');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Tipe Kamar gagal dihapus']);
        }
    }



    public function apiFetch(Request $request)
    {
        $query = TipeKamar::orderBy('nama', 'asc');

        if ($request->has('area_id') && empty($request->area_id)) {
            return response()->json([]);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $response = [];
        $area = $query->get();
        foreach ($area as $ar) {
            $response[] = [
                'id' => $ar->_id,
                'text' => $ar->nama,
                'nama' => $ar->nama,
                'harga' => $ar->harga,
                'fasilitas' => $ar->fasilitas,
            ];
        }
        return response()->json($response);
    }
}
