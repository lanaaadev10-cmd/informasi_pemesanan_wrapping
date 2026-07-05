<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $status = $this->status;
        
        return [
            'id_pembayaran' => $this->id_pembayaran,
            'id_pesanan' => $this->id_pesanan,
            'metode_pembayaran' => $this->metode_pembayaran,
            'jumlah_bayar' => (float) $this->jumlah_bayar,
            'status_pembayaran' => $status,
            'status_label' => $status ? \App\Enums\PaymentStatus::from($status)->label() : null,
            'status_badge_color' => $status ? \App\Enums\PaymentStatus::from($status)->badgeColor() : null,
            'bukti_transfer' => $this->bukti_transfer ? url('storage/' . $this->bukti_transfer) : null,
            'tgl_bayar' => $this->tgl_bayar?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
