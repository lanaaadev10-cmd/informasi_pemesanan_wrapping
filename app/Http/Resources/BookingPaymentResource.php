<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingPaymentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'payment_method' => $this->payment_method,
            'amount' => (float) $this->amount,
            'amount_formatted' => 'Rp ' . number_format((float) $this->amount, 0, ',', '.'),
            'proof_file' => $this->proof_file ? asset('storage/' . $this->proof_file) : null,
            'status' => $this->status,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'admin_notes' => $this->admin_notes,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}