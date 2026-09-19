<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Admin Booking Controller
 *
 * Endpoint admin-only untuk pengelolaan booking:
 * - GET  /api/admin/booking
 * - GET  /api/admin/booking/{id}
 * - POST /api/admin/booking/{id}/confirm
 * - POST /api/admin/booking/{id}/reject
 * - POST /api/admin/booking/{id}/start
 * - POST /api/admin/booking/{id}/complete
 * - POST /api/admin/booking/payment/{id}/verify
 * - POST /api/admin/booking/payment/{id}/reject
 */
class AdminBookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $perPage = min(100, max(1, $perPage));
        $status = $request->get('status');
        $date = $request->get('date');

        $bookings = $this->bookingService->getAllBookings(
            perPage: $perPage,
            status: $status,
            date: $date,
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data booking berhasil diambil',
            'data' => BookingResource::collection($bookings->items()),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'total' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'last_page' => $bookings->lastPage(),
            ],
        ], 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $booking = $this->bookingService->getBookingDetails((int) $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail booking berhasil diambil',
                'data' => new BookingResource($booking),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan.',
                'data' => null,
            ], 404);
        }
    }

    public function confirm(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $booking = $this->bookingService->confirmBooking($booking, $request->notes);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking dikonfirmasi. Customer diminta membayar.',
                'data' => new BookingResource($booking->load(['user', 'layanan', 'payment'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function reject(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        try {
            $booking = $this->bookingService->rejectBooking($booking, $request->notes);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking ditolak.',
                'data' => new BookingResource($booking->load(['user', 'layanan', 'payment'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function start(Request $request, Booking $booking): JsonResponse
    {
        try {
            $booking = $this->bookingService->startProcessing($booking, $request->notes);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan dimulai.',
                'data' => new BookingResource($booking->load(['user', 'layanan', 'payment'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function complete(Booking $booking): JsonResponse
    {
        try {
            $booking = $this->bookingService->completeBooking($booking);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai.',
                'data' => new BookingResource($booking->load(['user', 'layanan', 'payment'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function verifyPayment(Request $request, BookingPayment $bookingPayment): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $payment = $this->bookingService->verifyPayment($bookingPayment, $request->notes);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran terverifikasi. Booking disetujui.',
                'data' => new BookingResource($payment->booking->load(['user', 'layanan', 'payment'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function rejectPayment(Request $request, BookingPayment $bookingPayment): JsonResponse
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        try {
            $payment = $this->bookingService->rejectPayment($bookingPayment, $request->notes);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran ditolak. Booking kembali ke status menunggu pembayaran.',
                'data' => new BookingResource($payment->booking->load(['user', 'layanan', 'payment'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}