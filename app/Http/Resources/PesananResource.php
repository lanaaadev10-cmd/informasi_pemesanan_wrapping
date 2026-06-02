<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesananResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_pesanan' => $this->id_pesanan,
            'kode_pesanan' => $this->kode_pesanan,
            'id_user' => $this->id_user,
            'tanggal_pesan' => $this->tanggal_pesan->toIso8601String(),
            'status' => $this->status,
            'status_label' => $this->status ? \App\Enums\OrderStatus::from($this->status)->label() : null,
            'status_badge_color' => $this->status ? \App\Enums\OrderStatus::from($this->status)->badgeColor() : null,
            'total_harga' => (float) $this->total_harga,
            'catatan_admin' => $this->catatan_admin,
            
            // Relations
            'user' => new UserResource($this->whenLoaded('user')),
            'details' => DetailPesananResource::collection($this->whenLoaded('details')),
            'form' => new FormPesananResource($this->whenLoaded('form')),
            'pembayaran' => new PembayaranResource($this->whenLoaded('pembayaran')),
            'notifikasis' => NotifikasiResource::collection($this->whenLoaded('notifikasis')),
            
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
