<?php

namespace App\Exceptions\Booking;

use Illuminate\Http\JsonResponse;
use RuntimeException;

class BookingEmailUnverifiedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'Email Anda belum terverifikasi. Verifikasi email terlebih dahulu sebelum mengajukan booking.'
        );
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error_code' => 'EMAIL_NOT_VERIFIED',
        ], 403);
    }
}