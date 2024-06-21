<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\TagihanService;
use App\Models\Penyewa;
use App\Models\RiwayatKamar;
use App\Models\Sewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyewaController extends Controller
{
    private function getRedirectRoute()
    {
        return redirect()->route('penyewa.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['dataTable'] = Penyewa::orderBy('name', 'asc')->get();
        return view('admin.penyewa.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.penyewa.create');
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
        ]);
        $request->merge([
            'tanggal_bayar' => $request->tanggal_masuk,
            'foto_ktp' => null,
            'foto_penyewa' => null,
        ]);


        if ($request->exists('foto_ktp_upload') && $request->foto_ktp_upload != null) {
            $request->validate([
                'foto_ktp_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $fileName = $request->nama . '_ktp_' . time() . '_' . $request->foto_ktp_upload->getClientOriginalName();
            $path = Penyewa::IMAGE_PATH;
            ImageHelper::upload($request->foto_ktp_upload, $fileName, $path);
            $foto_ktp = $path . '/' . $fileName;

            $request->merge(['foto_ktp' => $foto_ktp]);
        }


        if ($request->exists('foto_penyewa_upload') && $request->foto_penyewa_upload != null) {
            $request->validate([
                'foto_penyewa_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $fileName = $request->nama . '_penyewa_' . time() . '_' . $request->foto_penyewa_upload->getClientOriginalName();
            $path = Penyewa::IMAGE_PATH;
            ImageHelper::upload($request->foto_penyewa_upload, $fileName, $path);
            $foto_penyewa = $path . '/' . $fileName;

            $request->merge(['foto_penyewa' => $foto_penyewa]);
        }

        $data = Penyewa::create($request->except(['foto_penyewa_upload', 'foto_ktp_upload']));
        if ($data) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil ditambah');
        } else {
            return redirect()->back()->withErrors(['error' => 'Data gagal ditambah']);
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
        $penyewa = Penyewa::find($id);
        if (!$penyewa) {
            return abort(404, 'Penyewa tidak ditemukan');
        }

        $data['dataTable_riwayat'] = RiwayatKamar::where('penyewa_id', $penyewa->id)->orderBy('created_at', 'desc')->get();
        $data['penyewa'] = $penyewa;
        $data['tunggakans'] = TagihanService::groupedInvoiceByUser(0, $penyewa->id);
        return view('admin.penyewa.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penyewa = Penyewa::find($id);
        if (!$penyewa) {
            return abort(404, 'Penyewa tidak ditemukan');
        }

        $data['penyewa'] = $penyewa;
        return view('admin.penyewa.edit', $data);
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
            'nama' => 'required',
            'nomor_wa' => 'required',
            'tanggal_masuk' => 'required',
            'pekerjaan' => 'required',
            'deskripsi' => 'present',
        ]);
        $request->merge([
            'tanggal_bayar' => $request->tanggal_masuk,
        ]);

        $penyewa = Penyewa::find($id);
        if (!$penyewa) {
            return redirect()->back()->withErrors(['error' => 'Data tidak ditemukan']);
        }

        if ($request->exists('foto_ktp_upload') && $request->foto_ktp_upload != null) {
            $request->validate([
                'foto_ktp_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // delete old photo
            Storage::delete('public/' . $penyewa->foto_ktp);

            $fileName = $request->nama . '_ktp_' . time() . '_' . $request->foto_ktp_upload->getClientOriginalName();
            $path = Penyewa::IMAGE_PATH;
            ImageHelper::upload($request->foto_ktp_upload, $fileName, $path);
            $foto_ktp = $path . '/' . $fileName;

            $request->merge(['foto_ktp' => $foto_ktp]);
        }


        if ($request->exists('foto_penyewa_upload') && $request->foto_penyewa_upload != null) {
            $request->validate([
                'foto_penyewa_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // delete old photo
            Storage::delete('public/' . $penyewa->foto_penyewa);

            $fileName = $request->nama . '_penyewa_' . time() . '_' . $request->foto_penyewa_upload->getClientOriginalName();
            $path = Penyewa::IMAGE_PATH;
            ImageHelper::upload($request->foto_penyewa_upload, $fileName, $path);
            $foto_penyewa = $path . '/' . $fileName;

            $request->merge(['foto_penyewa' => $foto_penyewa]);
        }

        $data = $penyewa->update($request->except(['foto_penyewa_upload', 'foto_ktp_upload']));
        if ($data) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil diperbarui');
        } else {
            return redirect()->back()->withErrors(['error' => 'Data gagal diperbarui']);
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
        //TODO: Gabisa dihapus
    }




    public function apiFetch(Request $request)
    {
        $query = Penyewa::orderBy('nama', 'asc');

        if ($request->has('kamar_id') && empty($request->kamar_id)) {
            return response()->json([]);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_wa', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kamar_id')) {
            $query->where('kamar_id', $request->kamar_id);
        }
        if ($request->has('is_belum_punya_kamar') && $request->filled('is_belum_punya_kamar')) {
            $sewa_aktif = Sewa::active()
                ->select('penyewa_id')
                ->groupBy('penyewa_id')
                ->pluck('penyewa_id');
            $query->whereNotIn('_id', $sewa_aktif);
        }

        $response = [];
        $penyewa = $query->get();
        foreach ($penyewa as $ar) {
            $response[] = $ar;
        }

        if ($request->has('type_request') && $request->type_request == 'select2') {
            $formatted = [];
            foreach ($response as $r) {
                $formatted[] = [
                    'id' => $r->id,
                    'text' => $r->nama,
                    'penyewa' => $r,
                ];
            }
            return response()->json($formatted);
        }

        return response()->json($response);
    }
}
