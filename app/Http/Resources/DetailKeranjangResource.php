<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailKeranjangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detail_keranjang' => $this->id_detail_keranjang,
            'id_keranjang' => $this->id_keranjang,
            'id_layanan' => $this->id_layanan,
            'quantity' => (int) $this->quantity,
            'harga_satuan' => (float) $this->harga_satuan,
            'subtotal' => (float) $this->subtotal,
            'custom_data' => $this->custom_data,
            
            'layanan' => new LayananResource($this->whenLoaded('layanan')),
            
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
