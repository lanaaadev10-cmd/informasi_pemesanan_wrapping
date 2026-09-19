<?php

namespace App\Http\Resources;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'user_id' => $this->user_id,
            'booking_date' => $this->booking_date?->toDateString(),
            'payment_type' => $this->payment_type,
            'payment_type_label' => $this->payment_type instanceof PaymentType
                ? $this->payment_type->label()
                : PaymentType::from($this->payment_type)->label(),
            'vehicle_name' => $this->vehicle_name,
            'vehicle_color' => $this->vehicle_color,
            'vehicle_license' => $this->vehicle_license,
            'notes' => $this->notes,
            'status' => $this->status instanceof BookingStatus ? $this->status->value : $this->status,
            'status_label' => $this->status instanceof BookingStatus
                ? $this->status->label()
                : BookingStatus::from($this->status)->label(),
            'status_badge_color' => $this->status instanceof BookingStatus
                ? $this->status->badgeColor()
                : BookingStatus::from($this->status)->badgeColor(),
            'admin_notes' => $this->admin_notes,

            'user' => new UserResource($this->whenLoaded('user')),
            'layanan' => new LayananResource($this->whenLoaded('layanan')),
            'payment' => new BookingPaymentResource($this->whenLoaded('payment')),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}