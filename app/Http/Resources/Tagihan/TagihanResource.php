<?php

namespace App\Http\Resources\Tagihan;

use Illuminate\Http\Resources\Json\JsonResource;

class TagihanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // list tagihan group by nama
        return [
            'penyewa' => [
                'id' => $this->penyewa->_id,
                'nama' => $this->penyewa->nama,
                'nomor_wa' => $this->penyewa->nomor_wa,
                'foto_penyewa' => $this->penyewa->foto_penyewa_url,
            ],
            'kamar' => [
                'id' => $this->kamar->_id,
                'area' => $this->kamar->area->judul,
                'nama' => $this->kamar->nama,
                'nomor' => $this->kamar->nomor,
                'lantai' => $this->kamar->lantai,
            ],
            'tanggal_tagihan_dibuat' => $this->tanggal_tagihan_dibuat,
            'tanggal_tagihan_dibuat_formatted' => $this->tanggal_tagihan_dibuat_formatted,
            'total_tagihan' => $this->total_tagihan,
            'sisa_tagihan' => $this->sisa_tagihan,
            'durasi' => $this->durasi,
            'status_label' => $this->status_label,
        ];
    }
}
