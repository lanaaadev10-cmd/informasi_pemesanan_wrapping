<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    /**
     * Buat booking lewat BookingService agar kuota, kode booking, dan
     * record pembayaran dibuat konsisten (tidak bisa bypass via Filament).
     */
    protected function handleRecordCreation(array $data): Model
    {
        $user = User::findOrFail($data['user_id']);

        $booking = app(BookingService::class)->createBooking($user, [
            'layanan_id'    => $data['layanan_id'],
            'booking_date'  => $data['booking_date'],
            'payment_type'  => $data['payment_type'],
            'vehicle_name'  => $data['vehicle_name'],
            'vehicle_color' => $data['vehicle_color'] ?? null,
            'vehicle_license' => $data['vehicle_license'] ?? null,
            'notes'         => $data['notes'] ?? null,
        ]);

        if (!empty($data['admin_notes'])) {
            $booking->update(['admin_notes' => $data['admin_notes']]);
        }

        return $booking->fresh();
    }
}