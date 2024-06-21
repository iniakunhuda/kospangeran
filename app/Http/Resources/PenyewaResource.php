<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenyewaResource extends JsonResource
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
            'nama' => $this->nama,
            'nomor_wa' => $this->nomor_wa,
            'tanggal_masuk' => $this->tanggal_masuk,
            'tanggal_bayar' => $this->tanggal_bayar,
            'pekerjaan' => $this->pekerjaan,
            'deskripsi' => $this->deskripsi,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'foto_ktp_url' => $this->foto_ktp_url,
            'foto_penyewa_url' => $this->foto_penyewa_url,
            'kamar' => $this->kamar,
        ];
    }
}
