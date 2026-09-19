<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Booking untuk user terautentikasi.
 *
 * Endpoints:
 * - GET  /api/booking            (daftar booking user)
 * - POST /api/booking            (buat booking) — throttle:3,1
 * - GET  /api/booking/{id}       (detail)
 * - POST /api/booking/{id}/upload-bukti — throttle:3,1
 * - POST /api/booking/{id}/cancel
 */
class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);
        $perPage = min(100, max(1, $perPage));
        $status = $request->get('status');

        $bookings = $this->bookingService->getUserBookings(
            userId: auth()->id(),
            perPage: $perPage,
            status: $status,
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
        $booking = Booking::with(['user', 'layanan', 'payment'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail booking berhasil diambil',
            'data' => new BookingResource($booking),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'layanan_id'      => 'required|exists:layanans,id_layanan',
            'booking_date'    => 'required|date|after_or_equal:today',
            'payment_type'    => 'required|in:dp,lunas',
            'payment_method'  => 'required|string|max:50',
            'vehicle_name'    => 'required|string|max:150',
            'vehicle_color'   => 'nullable|string|max:100',
            'vehicle_license' => 'nullable|string|max:50',
            'notes'           => 'nullable|string|max:1000',
            'proof_file'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                user: auth()->user(),
                data: $data,
                proofFile: $request->file('proof_file'),
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil diajukan',
                'data' => new BookingResource($booking->load(['user', 'layanan', 'payment'])),
            ], 201);
        } catch (\App\Exceptions\Booking\BookingFullException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error_code' => 'BOOKING_FULL',
                'data' => null,
            ], 409);
        } catch (\App\Exceptions\Booking\BookingDuplicateException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error_code' => 'BOOKING_DUPLICATE',
                'data' => null,
            ], 409);
        } catch (\App\Exceptions\Booking\BookingEmailUnverifiedException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error_code' => 'EMAIL_NOT_VERIFIED',
                'data' => null,
            ], 403);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat booking. Silakan periksa kembali data Anda.',
                'data' => null,
            ], 400);
        }
    }

    public function uploadBukti(Request $request, $id): JsonResponse
    {
        $request->validate([
            'payment_method' => 'required|string|max:50',
            'proof_file'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        try {
            $payment = $this->bookingService->uploadPaymentProof(
                booking: $booking,
                file: $request->file('proof_file'),
                paymentMethod: $request->payment_method,
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.',
                'data' => $payment,
            ], 200);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengunggah bukti. ' . $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function cancel(Request $request, $id): JsonResponse
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        try {
            $booking = $this->bookingService->cancelBooking($booking, $request->alasan);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil dibatalkan',
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
}