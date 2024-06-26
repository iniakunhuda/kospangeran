<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use App\Models\RiwayatBayar;
use Illuminate\Http\Request;

class MasterRekeningController extends Controller
{

    private function getRedirectRoute()
    {
        return redirect(route('master.rekening.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataTable = Rekening::orderBy('nama_pembayaran', 'asc')->get();
        return view('admin.master_rekening.index', [
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
        return view('admin.master_rekening.create');
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

        $result = Rekening::create($request->all());
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil disimpan');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Data gagal disimpan']);
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
        $rekening = Rekening::where('_id', $id)->first();
        if (!$rekening) {
            return abort(404, 'Rekening Tidak Ditemukan');
        }

        return view('admin.master_rekening.show', [
            'rekening' => $rekening,
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
        $rekening = Rekening::find($id);
        if (!$rekening) {
            return abort(404, 'Rekening Tidak Ditemukan');
        }
        return view('admin.master_rekening.edit', [
            'rekening' => $rekening
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
            'nama_pembayaran' => 'required',
            'nama_rekening' => 'required',
            'nomor_rekening' => 'required',
        ]);

        $rekening = Rekening::find($id);
        if (!$rekening) {
            return abort(404, 'Rekening Tidak Ditemukan');
        }
        $result = $rekening->update($request->all());
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil diperbarui');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Data gagal diperbarui']);
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
        $rekening = Rekening::where('_id', $id)->first();
        if (!isset($rekening)) {
            return abort(404, 'Rekening Tidak Ditemukan');
        }

        $riwayat_bayar = RiwayatBayar::where('rekening_id', $rekening->_id)->first();
        if ($riwayat_bayar) {
            return $this->getRedirectRoute()->withErrors(['error' => 'Data gagal dihapus, karena sudah ada transaksi']);
        }

        $result = $rekening->delete();
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil dihapus');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Data gagal dihapus']);
        }
    }
}
