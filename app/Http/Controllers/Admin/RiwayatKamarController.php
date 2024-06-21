<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaKos;
use App\Models\Penyewa;
use App\Models\RiwayatKamar;
use App\Models\Kamar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiwayatKamarController extends Controller
{
    private function getRedirectRoute()
    {
        return redirect()->route('riwayat_kamar.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter_area = AreaKos::orderBy('judul', 'asc')->get();
        $filter_penyewa = Penyewa::orderBy('nama', 'asc')->get();
        $filter_kamars = Kamar::orderBy('nama', 'asc')->get();

        $dataTable_riwayat = RiwayatKamar::orderBy('created_at', 'desc');
        if (request()->filled('area_id')) {
            $dataTable_riwayat->where('area_id', request()->area_id);
        }
        if (request()->filled('penyewa_id')) {
            $dataTable_riwayat->where('penyewa_id', request()->penyewa_id);
        }
        if (request()->filled('kamar_id')) {
            $dataTable_riwayat->where('kamar_id', request()->kamar_id);
        }
        if (request()->filled('tanggal')) {
            $tanggal = request()->tanggal;
            $dataTable_riwayat->whereBetween(
                'tanggal',
                [
                    new Carbon($tanggal . ' ' . '00:00:00'),
                    new Carbon($tanggal . ' ' . '23:59:59')
                ]
            );
        }

        return view('admin.riwayat_kamar.index', [
            'dataTable_riwayat' => $dataTable_riwayat->get(),
            'filter_areas' => $filter_area,
            'filter_penyewas' => $filter_penyewa,
            'filter_kamars' => $filter_kamars,
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
        $data['kamars'] = Kamar::orderBy('nama', 'asc')->get();
        return view('admin.riwayat_kamar.create', $data);
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
            'kamar_id' => ['required', 'exists:kamar,_id'],
            'penyewa_id' => 'present',
            'tanggal' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required',
        ]);

        if ($request->kategori == RiwayatKamar::KATEGORI_RIWAYAT_BERHENTI_SEWA) {
            $request->validate([
                'catatan_berhenti' => 'required',
                'tanggal_berhenti' => 'required',
            ]);
        }

        $result = RiwayatKamar::create($request->except('penyewa_nama'));
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Riwayat berhasil ditambah');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Riwayat gagal ditambah']);
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
