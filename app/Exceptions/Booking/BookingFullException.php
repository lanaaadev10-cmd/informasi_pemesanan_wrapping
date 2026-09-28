<?php

namespace App\Exceptions\Booking;

use Illuminate\Http\JsonResponse;
use RuntimeException;

class BookingFullException extends RuntimeException
{
    public function __construct(string $date, int $maxBookings = 4)
    {
        $tanggal = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');

        parent::__construct(
            "Kuota booking untuk {$tanggal} sudah penuh ({$maxBookings}/{$maxBookings} slot terisi). Silakan pilih tanggal lain."
        );
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error_code' => 'BOOKING_FULL',
        ], 409);
    }
}