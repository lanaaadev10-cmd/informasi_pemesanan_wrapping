<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPesananResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detail_pesanan' => $this->id_detail_pesanan,
            'id_pesanan' => $this->id_pesanan,
            'id_layanan' => $this->id_layanan,
            'quantity' => (int) $this->quantity,
            'harga_satuan' => (float) $this->harga_satuan,
            'subtotal' => (float) $this->subtotal,
            'custom_data' => $this->custom_data,
            
            'layanan' => new LayananResource($this->whenLoaded('layanan')),
            
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
