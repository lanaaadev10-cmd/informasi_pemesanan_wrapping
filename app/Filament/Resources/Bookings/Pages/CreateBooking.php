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
        $override = (bool) ($data['override_quota'] ?? false);
        return app(BookingService::class)->createManualBooking($data, $override);
    }
}