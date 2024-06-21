<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Base64Helper;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\SewaService;
use App\Models\AreaKos;
use App\Models\Kamar;
use App\Models\Sewa;
use App\Models\TipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    private function getRedirectRoute()
    {
        return redirect()->route('kamar.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter_area = AreaKos::orderBy('judul', 'asc')->get();
        $filter_tipe_kamar = TipeKamar::orderBy('nama', 'asc')->get();

        $dataTable_kamar = Kamar::orderBy('status', 'asc')->orderBy('nama', 'asc');
        if (request()->filled('area_id')) {
            $dataTable_kamar->where('area_id', request()->area_id);
        }
        if (request()->filled('is_kamar_kosong')) {
            $dataTable_kamar->where('status', Kamar::STATUS_KOSONG);
        }

        return view('admin.kamar.index', [
            'dataTable_kamar' => $dataTable_kamar->get(),
            'filter_areas' => $filter_area,
            'filter_tipe_kamar' => $filter_tipe_kamar
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.kamar.create');
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
            'durasi' => 'required',
            'deskripsi' => 'present',
        ]);

        $request->merge([
            'status' => Kamar::STATUS_KOSONG,
            'penyewa' => null,
        ]);

        if (!in_array($request->status, Kamar::STATUS)) {
            return redirect()->back()->withErrors(['error' => 'Status Kamar tidak valid']);
        }

        // upload foto
        $fotos = [];
        if ($request->exists('image') && (count($request->image) > 0)) {
            $request->validate([
                'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            foreach ($request->image as $key => $foto) {
                $fileName = $request->nama . '_' . time() . '_' . $foto->getClientOriginalName();
                $path = Kamar::IMAGE_PATH;
                ImageHelper::upload($foto, $fileName, $path);
                $fotos[] = $path . '/' . $fileName;
            }
            $request->merge(['foto' => $fotos]);
        }

        $data = Kamar::create($request->all());
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
        $kamar = Kamar::find($id);
        if (!$kamar) {
            return abort(404, 'Kamar tidak ditemukan');
        }

        $data['kamar'] = $kamar;
        $data['sewa'] = Sewa::active()->where('kamar_id', $id)->where('penyewa_id', $kamar->penyewa_id)->first();
        return view('admin.kamar.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kamar = Kamar::find($id);
        if (!$kamar) {
            return abort(404, 'Kamar tidak ditemukan');
        }

        $data['kamar'] = $kamar;
        return view('admin.kamar.edit', $data);
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
            'area_id' => ['required', 'exists:area_kos,_id'],
            'tipe_kamar_id' => ['required', 'exists:tipe_kamar,_id'],
            'nama' => 'required',
            'nomor' => 'required',
            'lantai' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',
            'durasi' => 'required',
            'deskripsi' => 'present',
        ]);

        $kamar = Kamar::find($id);
        if (!$kamar) {
            return redirect()->back()->withErrors(['error' => 'Data tidak ditemukan']);
        }

        // upload foto
        $result = false;
        $fotos = [];
        if ($request->exists('image') && (count($request->image) > 0)) {
            $request->validate([
                'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // remove old photos
            $oldPhotos = $kamar->image;
            foreach ($oldPhotos as $oldPhoto) {
                Storage::delete('public/' . $oldPhoto);
            }

            foreach ($request->image as $key => $foto) {
                $fileName = $request->nama . '_' . time() . '_' . $foto->getClientOriginalName();
                $path = Kamar::IMAGE_PATH;
                ImageHelper::upload($foto, $fileName, $path);
                $fotos[] = $path . '/' . $fileName;
            }
            $request->merge(['foto' => $fotos]);
            $result = $kamar->update($request->all());
        } else {
            $result = $kamar->update($request->except('image'));
        }

        // trigger harga sewa
        // kalau ada transaksi sewa yg aktif sesuai kamar_id, update harga jg
        SewaService::updateSewaPriceWhenKamarUpdatted($kamar, $request->harga, $request->durasi);


        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil diubah');
        } else {
            return redirect()->back()->withErrors(['error' => 'Data gagal diubah']);
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
        $kamar = Kamar::find($id);
        if (!$kamar) {
            return $this->getRedirectRoute()->withErrors(['error' => 'Data gagal dihapus']);
        }

        if (!$kamar->is_allow_delete) {
            return $this->getRedirectRoute()->withErrors(['error' => 'Kamar tidak dapat dihapus karena sudah ada riwayat sewa dan perbaikan kamar']);
        }

        // remove old photos
        if ((count($kamar->foto) > 0)) {
            $oldPhotos = $kamar->foto;
            foreach ($oldPhotos as $oldPhoto) {
                Storage::delete('public/' . $oldPhoto);
            }
        }

        $result = $kamar->delete();
        if ($result) {
            return $this->getRedirectRoute()->with('success', 'Data berhasil dihapus');
        } else {
            return $this->getRedirectRoute()->withErrors(['error' => 'Data gagal dihapus']);
        }
    }


    public function riwayatPenyewaIndex($id)
    {
        $kamar = Kamar::find($id);
        if (!$kamar) {
            return abort(404, 'Kamar tidak ditemukan');
        }

        $data['kamar'] = $kamar;
        return view('admin.kamar.riwayat_penyewa.index', $data);
    }

    public function apiFetch(Request $request)
    {
        $query = Kamar::with(['penyewa', 'area'])->orderBy('nama', 'asc');

        if ($request->has('area_id') && empty($request->area_id)) {
            return response()->json([]);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->has('is_kosong') && $request->filled('is_kosong')) {
            $query->where('penyewa_id', null);
        }

        $response = [];
        $kamar = $query->get();
        foreach ($kamar as $ar) {
            $response[] = $ar;
        }

        if ($request->has('type_request') && $request->type_request == 'select2') {
            $formatted = [];
            foreach ($response as $r) {
                $formatted[] = [
                    'id' => $r->id,
                    'text' => $r->nama,
                    'kamar' => $r,
                ];
            }
            return response()->json($formatted);
        }

        return response()->json($response);
    }
}
