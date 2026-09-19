<?php

namespace App\Exceptions\Booking;

use Illuminate\Http\JsonResponse;
use RuntimeException;

class BookingDuplicateException extends RuntimeException
{
    public function __construct(string $date)
    {
        $tanggal = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');

        parent::__construct(
            "Anda sudah memiliki booking aktif pada {$tanggal}. Selesaikan atau batalkan booking tersebut sebelum mengajukan baru."
        );
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error_code' => 'BOOKING_DUPLICATE',
        ], 409);
    }
}