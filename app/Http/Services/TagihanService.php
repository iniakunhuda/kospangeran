<?php

namespace App\Http\Services;

use App\Models\AreaKos;
use App\Models\Kamar;
use App\Models\Penyewa;
use App\Models\RiwayatBayar;
use App\Models\RiwayatPersewaan;
use App\Models\Sewa;
use App\Models\Tagihan;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class TagihanService
{

    public static function getPenyewaBelumBayar()
    {
        $result = self::groupedInvoiceByUser();
        if (!$result) {
            return collect([]);
        }

        $penyewas = [];
        foreach ($result as $key => $value) {
            $penyewas[$value['penyewa']['_id']] = $value['penyewa'];
        }

        return collect(array_values($penyewas));
    }


    public static function createNewInvoice(Sewa $sewa)
    {
        $kamar = Kamar::find($sewa->kamar_id);
        if (!$kamar) {
            throw new \Exception('Kamar tidak ditemukan');
        }

        $penyewa = Penyewa::find($sewa->penyewa_id);
        if (!$penyewa) {
            throw new \Exception('Penyewa tidak ditemukan');
        }

        return Tagihan::create([
            'area_id' => $sewa->area_id,
            'kamar_id' => $sewa->kamar_id,
            'penyewa_id' => $sewa->penyewa_id,
            'tanggal_tagihan_dibuat' => $sewa->tanggal_bayar,
            'total_tagihan' => $sewa->total_bayar,
            'sisa_tagihan' => $sewa->total_bayar,
            'durasi' => $sewa->durasi,
            'status' => Tagihan::STATUS_BAYAR_BELUM_BAYAR
        ]);
    }


    public static function groupedInvoiceByUser(int $status = 0, String $penyewa_id = '', String $area_id = '', String $kamar_id = '', String $durasi = '')
    {
        $tagihan = Tagihan::active()->orderBy('tanggal_tagihan_dibuat', 'asc');

        if ($status == Tagihan::STATUS_BAYAR_BELUM_BAYAR) {
            $tagihan->unpaid();
        } else if ($status == Tagihan::STATUS_BAYAR_LUNAS) {
            $tagihan->paid();
        } else if ($status == Tagihan::STATUS_BAYAR_BAYAR_SEBAGIAN) {
            $tagihan->partialPaid();
        }

        if ($area_id != '') {
            $area = AreaKos::find($area_id);
            if (!$area) {
                throw new Exception('Area Kos tidak ditemukan');
            }
            $tagihan->where('area_id', $area_id);
        }

        if ($penyewa_id != '') {
            $penyewa = Penyewa::find($penyewa_id);
            if (!$penyewa) {
                throw new Exception('Penyewa tidak ditemukan');
            }
            $tagihan->where('penyewa_id', $penyewa_id);
        }

        if ($kamar_id != '') {
            $kamar = Kamar::find($kamar_id);
            if (!$kamar) {
                throw new Exception('Kamar tidak ditemukan');
            }
            $tagihan->where('kamar_id', $kamar_id);
        }

        $list_tagihan = $tagihan->get();
        if (count($list_tagihan) < 1) return [];

        $group_tagihan = [];
        foreach ($list_tagihan as $tagihan) {
            $key = $tagihan->penyewa->_id . '_' . $tagihan->kamar->_id;
            $rincian = [
                '_id' => $tagihan->_id,
                'tanggal_tagihan_dibuat' => $tagihan->tanggal_tagihan_dibuat,
                'tanggal_tagihan_dibuat_formatted' => $tagihan->tanggal_tagihan_dibuat_formatted,
                'total_tagihan' => $tagihan->total_tagihan,
                'sisa_tagihan' => $tagihan->sisa_tagihan,
                'durasi' => $tagihan->durasi,
                'status_label' => $tagihan->status_label,
            ];

            if (!isset($group_tagihan[$key])) {
                $group_tagihan[$key] = [
                    'penyewa' => $tagihan->penyewa,
                    'kamar' => $tagihan->kamar,
                    'area' => $tagihan->kamar->area,
                    'total_tagihan' => 0,
                    'tagihan' => []
                ];
            }

            if ($durasi != '' && $durasi != $tagihan->durasi) {
                continue;
            }
            $group_tagihan[$key]['tagihan'][] = $rincian;
            $group_tagihan[$key]['total_tagihan'] += $rincian['sisa_tagihan'];
        }

        return array_values($group_tagihan);
    }


    /**
     * [v] Normal bayar
     * [v] Bayar separo
     * TODO: [ ] Bayar bulan depan sekalian
     */
    public static function payInvoice(String $penyewa_id, String $area_id, String $kamar_id, String $durasi, int $total_bayar)
    {
        $invoice_penyewa = self::groupedInvoiceByUser(
            Tagihan::STATUS_BAYAR_BELUM_BAYAR,
            $penyewa_id,
            $area_id,
            $kamar_id,
            $durasi
        );

        if (count($invoice_penyewa) < 1) {
            throw new Exception('Tagihan tidak ditemukan');
        }

        $response = [];

        $total_bayar = intVal($total_bayar);
        foreach ((array) $invoice_penyewa[0]['tagihan'] as $tagihan) {
            if ($total_bayar <= 0) {
                break;
            }

            $tmp_sisa = $tagihan['sisa_tagihan'] - $total_bayar;
            if ($tmp_sisa <= 0) {
                $status = Tagihan::STATUS_BAYAR_LUNAS;
                $tagihan_yg_dibayar = $tagihan['sisa_tagihan'];
                Tagihan::active()->where('_id', $tagihan['_id'])->update([
                    'status' => $status,
                    'sisa_tagihan' => 0,
                ]);
            } else {
                $status = Tagihan::STATUS_BAYAR_BAYAR_SEBAGIAN;
                $tagihan_yg_dibayar = $tagihan['sisa_tagihan'] - $tmp_sisa;
                Tagihan::active()->where('_id', $tagihan['_id'])->update([
                    'status' => $status,
                    'sisa_tagihan' => $tmp_sisa,
                ]);
            }

            $response[] = [
                'tagihan_id' => $tagihan['_id'],
                'total_tagihan' => (int) $tagihan['sisa_tagihan'],
                'total_bayar' => (int) $tagihan_yg_dibayar,
                'sisa_tagihan' => ($tmp_sisa < 0) ? 0 : (int) $tmp_sisa,
                'status' => $status,
            ];
            $total_bayar -= $tagihan['sisa_tagihan'];
        }

        // TODO: if total bayar masih ada, create new tagihan berdasarkan durasi (bulanan / tahunan)
        if ($total_bayar > 0) {
        }

        return $response;
        // return id tagihan, total pembayaran, dan status
    }
}
