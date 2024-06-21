<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipeKamarResource extends JsonResource
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
            'id' => $this->id,
            'area' => $this->area,
            'nama' => $this->nama,
            'kode' => $this->kode,
            'harga' => $this->harga,
            'fasilitas' => $this->fasilitas ?? [],
            'status' => $this->status,
            'total_kamar' => $this->total_kamar,
            'kamar' => $this->kamar ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
