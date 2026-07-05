<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeranjangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_keranjang' => $this->id_keranjang,
            'id_user' => $this->id_user,
            'status' => $this->status,
            'total_items' => $this->details?->count() ?? 0,
            'total_harga' => (float) $this->details?->sum('subtotal') ?? 0,
            
            'details' => DetailKeranjangResource::collection($this->whenLoaded('details')),
            
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
