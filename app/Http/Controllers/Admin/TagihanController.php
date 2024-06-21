<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CarbonDateHelper;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\TagihanService;
use App\Models\AreaKos;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\Rekening;
use App\Models\RiwayatBayar;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function belumBayarIndex(Request $request)
    {
        $status = $request->status ?? '0';
        $area_id = $request->area_id ?? '';
        $penyewa_id = $request->penyewa_id ?? '';
        $kamar_id = $request->kamar_id ?? '';
        $durasi = $request->durasi ?? '';

        $formatted = TagihanService::groupedInvoiceByUser(
            $status,
            $penyewa_id,
            $area_id,
            $kamar_id,
            $durasi
        );

        $data['filter_areas'] = AreaKos::orderBy('judul', 'asc')->get();
        $data['filter_kamars'] = Kamar::orderBy('nama', 'asc')->get();
        $data['filter_penyewas'] = Penyewa::orderBy('nama', 'asc')->get();
        $data['dataTable'] = $formatted;

        $data['request'] = $request->all();
        return view('admin.tagihan.belum_bayar.index', $data);
    }

    public function belumBayarDestroy($id)
    {
        $rekening = Tagihan::where('_id', $id)->first();
        if (!isset($rekening)) {
            return abort(404, 'Tagihan Tidak Ditemukan');
        }
        $result = $rekening->update(['is_deleted' => true]);
        if ($result) {
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->withErrors(['error' => 'Data gagal dihapus']);
        }
    }


    public function bayarCreate(Request $request)
    {
        $data['rekenings'] = Rekening::orderBy('nama_pembayaran', 'asc')->get();
        $data['penyewas'] = TagihanService::getPenyewaBelumBayar();
        $data['tagihan'] = null;

        if ($request->filled('penyewa_id')) {
            $tagihans = TagihanService::groupedInvoiceByUser(0, $request->penyewa_id);
            $data['tagihan'] = $tagihans;

            $areas = [];
            $kamars = [];
            foreach ($tagihans as $tagihan) {
                $areas[$tagihan['area']['id']] = $tagihan['area'];
                $kamars[$tagihan['kamar']['id']] = $tagihan['kamar'];
            }
            $data['areas'] = collect($areas);
            $data['kamars'] = collect($kamars);
        }

        return view('admin.tagihan.bayar.create', $data);
    }


    public function bayarStore(Request $request)
    {
        $request->validate([
            'penyewa_id' => ['required', 'exists:penyewa,_id'],
            'area_id' => ['required', 'exists:area_kos,_id'],
            'kamar_id' => ['required', 'exists:kamar,_id'],
            'rekening_id' => ['required', 'exists:rekening,_id'],
            'total_bayar' => 'required',
            'tanggal_bayar' => 'required',
            'durasi' => 'required',
            'deskripsi' => 'present',
            'bukti_pembayaran_upload' => 'required',
        ]);

        $area = AreaKos::find($request->area_id);
        $penyewa = Penyewa::find($request->penyewa_id);
        $kamar = Kamar::with('tipe_kamar')->find($request->kamar_id);

        if (!$penyewa || !$kamar || !$area) {
            return redirect()->back()->withErrors(['error' => 'Pembayaran gagal, data tidak ditemukan']);
        }

        $request->merge([
            'tanggal_bayar' => CarbonDateHelper::formatDateStringToMongodate($request->tanggal_bayar),
            'penyewa' => $penyewa,
            'kamar' => $kamar,
            'area' => $area,
        ]);


        if ($request->exists('bukti_pembayaran_upload') && $request->bukti_pembayaran_upload != null) {
            $request->validate([
                'bukti_pembayaran_upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $fileName = $request->penyewa_id . '_' . time() . '_' . $request->bukti_pembayaran_upload->getClientOriginalName();
            $path = RiwayatBayar::IMAGE_PATH;
            ImageHelper::upload($request->bukti_pembayaran_upload, $fileName, $path);
            $foto_penyewa = $path . '/' . $fileName;

            $request->merge(['bukti_pembayaran' => $foto_penyewa]);
        }

        $tagihan_terbayar = TagihanService::payInvoice(
            $penyewa->_id,
            $area->id,
            $kamar->_id,
            $request->durasi,
            $request->total_bayar
        );
        if (!$tagihan_terbayar) {
            return redirect()->back()->withErrors(['error' => 'Pembayaran gagal disimpan']);
        }
        $request->merge(['detail_tagihan' => $tagihan_terbayar]);


        $result = RiwayatBayar::create($request->except(['bukti_pembayaran_upload', 'status']));
        if ($result) {
            return redirect()->back()->with('success', 'Pembayaran berhasil disimpan');
        } else {
            return redirect()->back()->withErrors(['error' => 'Pembayaran gagal disimpan']);
        }
    }


    public function riwayatIndex(Request $request)
    {
        $riwayat = RiwayatBayar::orderBy('created_at', 'desc');

        if ($request->filled('penyewa_id')) {
            $riwayat->where('penyewa_id', $request->penyewa_id);
        }

        if ($request->filled('area_id')) {
            $riwayat->where('area_id', $request->area_id);
        }

        if ($request->filled('kamar_id')) {
            $riwayat->where('kamar_id', $request->kamar_id);
        }

        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $tgl_akhir_1 = date('Y-m-d', strtotime($request->tgl_akhir . ' +1 day'));
            $riwayat->whereBetween('tanggal_bayar', [
                CarbonDateHelper::formatDateStringToMongodate($request->tgl_awal),
                CarbonDateHelper::formatDateStringToMongodate($tgl_akhir_1)
            ]);
            $format_awal = Carbon::parse($request->tgl_awal)->format('d M Y');
            $format_akhir = Carbon::parse($request->tgl_akhir)->format('d M Y');
            $data['pageTitle'] = "Riwayat Pembayaran " . $format_awal . " - " . $format_akhir;
        } else {
            $data['pageTitle'] = "Riwayat Pembayaran";
        }

        $data['filter_areas'] = AreaKos::orderBy('judul', 'asc')->get();
        $data['filter_kamars'] = Kamar::orderBy('nama', 'asc')->get();
        $data['filter_penyewas'] = Penyewa::orderBy('nama', 'asc')->get();
        $data['dataTable'] = $riwayat->get();

        return view('admin.tagihan.riwayat.index', $data);
    }


    public function riwayatShow($id)
    {
        $riwayat = RiwayatBayar::where('_id', $id)->first();
        if (!isset($riwayat)) {
            return abort(404, 'Riwayat Tidak Ditemukan');
        }

        $tagihan_groups = [];
        $tagihan = Tagihan::get();

        foreach ($tagihan as $key => $value) {
            $tagihan_groups[$value['_id']] = $value;
        }
        $data['tagihan_groups'] = $tagihan_groups;
        $data['data'] = $riwayat;
        return view('admin.tagihan.riwayat.show', $data);
    }
}
