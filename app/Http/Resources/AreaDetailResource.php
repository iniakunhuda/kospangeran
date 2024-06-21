<?php

namespace App\Http\Resources;

use App\Models\Kamar;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $statistik = [
            'jumlah_kamar' => $this->kamar->count() ?? 0,
            'jumlah_kamar_kosong' => $this->kamar->where('status', Kamar::STATUS_KOSONG)->count() ?? 0,
            'estimasi_pendapatan' => $this->kamar->where('status', Kamar::STATUS_TERISI)->sum('harga') ?? 0,
        ];

        return [
            '_id' => $this->_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'jenis' => $this->jenis,
            'alamat' => $this->alamat,
            'fasilitas' => $this->fasilitas ?? [],
            'tipe_kamar' => $this->tipe_kamar->append(['status', 'total_kamar'])->makeHidden('kamar') ?? [],
            'statistik' => $statistik ?? null,
        ];
    }
}
