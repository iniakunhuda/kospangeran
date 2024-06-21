<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KamarResource extends JsonResource
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
        return [
            '_id' => $this->_id,
            'area' => $this->area,
            'nama' => $this->nama,
            'nomor' => $this->nomor,
            'lantai' => $this->lantai,
            'tipe_kamar' => $this->tipe_kamar,
            'harga' => $this->harga,
            'fasilitas' => $this->fasilitas,
            'deskripsi' => $this->deskripsi,
            'foto_url' => $this->foto_url,
            'status' => $this->status,
            'penyewa' => $this->penyewa ?? null,
            'riwayat' => $this->riwayat ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
