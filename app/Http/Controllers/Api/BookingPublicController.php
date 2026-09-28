<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

/**
 * Endpoint publik untuk informasi kuota booking (landing page).
 * Tanpa autentikasi, hanya read-only data kuota.
 */
class BookingPublicController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function getTodayQuota(): JsonResponse
    {
        return response()->json([
            'date' => now()->toDateString(),
            'quota' => $this->bookingService->getTodayQuota(),
        ]);
    }

    public function checkQuota(string $date): JsonResponse
    {
        if (!strtotime($date)) {
            return response()->json([
                'message' => 'Format tanggal tidak valid. Gunakan Y-m-d.',
                'error_code' => 'INVALID_DATE',
            ], 422);
        }

        return response()->json([
            'date' => $date,
            'quota' => $this->bookingService->checkQuota($date),
        ]);
    }

    public function getMonthQuota(int $year, int $month): JsonResponse
    {
        if ($month < 1 || $month > 12) {
            return response()->json([
                'message' => 'Bulan harus antara 1-12.',
                'error_code' => 'INVALID_MONTH',
            ], 422);
        }

        return response()->json([
            'year' => $year,
            'month' => $month,
            'quota' => $this->bookingService->getMonthQuota($year, $month),
        ]);
    }
}