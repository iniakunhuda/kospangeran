<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RiwayatKamarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            '_id' => $this->_id,
            'nama_area' => $this->area->judul,
            'nama_kamar' => $this->kamar->nama,
            'nama_penyewa' => $this->penyewa->nama,
            'tanggal' => $this->tanggal,
            'deskripsi' => $this->deskripsi,
            'kategori' => $this->kategori,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
