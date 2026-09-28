<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Layanan;
use App\Services\BookingService;
use App\Enums\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    /**
     * Daftar booking milik user yang login.
     */
    public function index(Request $request)
    {
        $bookings = $this->bookingService->getUserBookings(
            userId: Auth::id(),
            perPage: 10,
            status: $request->status ?: null,
        );

        $stats = $this->bookingService->getUserBookingStats(Auth::id());

        return view('customer.booking.index', compact('bookings', 'stats'));
    }

    /**
     * Form pengajuan booking.
     */
    public function create(Request $request)
    {
        // Gate verifikasi email sebelum mengajukan booking (anti slot-hoarding).
        if (Auth::user()->email_verified_at === null) {
            return redirect()->route('verification.notice')
                ->with('toast_warning', 'Verifikasi email Anda terlebih dahulu untuk mengajukan booking.');
        }

        $layanans = $this->bookingService->getAvailableLayanans();
        $selectedDate = $request->get('date', now()->toDateString());
        $todayQuota = $this->bookingService->getTodayQuota();

        // Validasi tanggal yang dipilih tidak FULL.
        $quota = $this->bookingService->checkQuota($selectedDate);
        if ($quota['is_full']) {
            $selectedDate = now()->addDay()->toDateString();
        }

        return view('customer.booking.create', compact('layanans', 'selectedDate', 'todayQuota'));
    }

    /**
     * Proses simpan booking (dengan anti-overbooking di service).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'layanan_id'       => 'required|exists:layanans,id_layanan',
            'booking_date'     => 'required|date|after_or_equal:today',
            'payment_type'     => 'required|in:dp,lunas',
            'payment_method'   => 'required|string|max:50',
            'vehicle_name'     => 'required|string|max:150',
            'vehicle_color'    => 'nullable|string|max:100',
            'vehicle_license'  => 'nullable|string|max:50',
            'notes'            => 'nullable|string|max:1000',
            'proof_file'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                user: Auth::user(),
                data: $data,
                proofFile: $request->file('proof_file'),
            );

            return redirect()->route('booking.show', $booking->id)
                ->with('toast_success', 'Booking berhasil diajukan! Kode booking: ' . $booking->booking_code);
        } catch (\App\Exceptions\SlotPenuhException $e) {
            return back()->with('toast_error', $e->getMessage())->withInput();
        } catch (\App\Exceptions\Booking\BookingFullException $e) {
            return back()->with('toast_error', $e->getMessage())->withInput();
        } catch (\App\Exceptions\Booking\BookingDuplicateException $e) {
            return back()->with('toast_error', $e->getMessage())->withInput();
        } catch (\App\Exceptions\Booking\BookingEmailUnverifiedException $e) {
            return redirect()->route('verification.notice')
                ->with('toast_warning', 'Verifikasi email Anda terlebih dahulu untuk mengajukan booking.');
        } catch (\Exception $e) {
            return back()->with('toast_error', 'Gagal membuat booking: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Detail satu booking.
     */
    public function show($id)
    {
        $query = Booking::with(['user', 'layanan', 'payment'])
            ->where('id', $id);

        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $booking = $query->firstOrFail();

        return view('customer.booking.show', compact('booking'));
    }

    /**
     * Upload bukti transfer (status: awaiting_payment -> payment_uploaded).
     */
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|max:50',
            'proof_file'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $this->bookingService->uploadPaymentProof(
                booking: $booking,
                file: $request->file('proof_file'),
                paymentMethod: $request->payment_method,
            );

            return back()->with('toast_success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('toast_error', 'Gagal mengunggah bukti: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan booking oleh user (jika status mengizinkan).
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $this->bookingService->cancelBooking($booking, $request->alasan);

            return back()->with('toast_success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('toast_error', $e->getMessage());
        }
    }
}