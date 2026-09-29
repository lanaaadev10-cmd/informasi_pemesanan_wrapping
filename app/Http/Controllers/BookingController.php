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
        $status = $request->status ?: null;
        $tab = $request->tab ?: ($status ? null : 'all');

        $bookings = $this->bookingService->getUserBookings(
            userId: Auth::id(),
            perPage: 10,
            status: $status,
            tab: $tab,
        );

        $stats = $this->bookingService->getUserBookingStats(Auth::id());

        return view('customer.booking.index', compact('bookings', 'stats', 'tab', 'status'));
    }

    /**
     * Form pengajuan booking.
     */
    public function create(Request $request)
    {
        $layanans = $this->bookingService->getAvailableLayanans();
        $selectedDate = $request->get('date', now()->toDateString());
        $todayQuota = $this->bookingService->getTodayQuota();

        // Validasi tanggal yang dipilih tidak FULL.
        $quota = $this->bookingService->checkQuota($selectedDate);
        if ($quota['is_full']) {
            $selectedDate = now()->addDay()->toDateString();
        }

        $user = Auth::user();

        return view('customer.booking.create', compact('layanans', 'selectedDate', 'todayQuota', 'user'));
    }

    /**
     * Proses simpan booking (dengan anti-overbooking di service).
     */
    public function store(Request $request)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('toast_warning', 'Email Anda belum terverifikasi. Verifikasi email terlebih dahulu sebelum mengajukan booking.');
        }

        if (!$request->filled('customer_name') && Auth::check()) {
            $request->merge(['customer_name' => Auth::user()->name]);
        }
        if (!$request->filled('customer_phone') && Auth::check()) {
            $phone = Auth::user()->no_hp ?: (Auth::user()->phone ?: '081234567890');
            $request->merge(['customer_phone' => $phone]);
        }

        $data = $request->validate([
            'customer_name'    => 'required|string|max:150',
            'customer_phone'   => 'required|string|max:30',
            'customer_email'   => 'nullable|email|max:150',
            'layanan_id'       => 'required|exists:layanans,id_layanan',
            'booking_date'     => 'required|date|after_or_equal:today',
            'booking_time'     => 'nullable|string|max:10',
            'payment_type'     => 'required|in:dp,lunas',
            'payment_method'   => 'nullable|string|max:50',
            'vehicle_name'     => 'nullable|string|max:150',
            'vehicle_color'    => 'nullable|string|max:100',
            'vehicle_license'  => 'nullable|string|max:50',
            'notes'            => 'nullable|string|max:1000',
            'proof_file'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'layanan_id.required' => 'Pilih paket layanan yang diinginkan.',
            'booking_date.required' => 'Pilih tanggal booking yang tersedia.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                user: Auth::user(),
                data: $data,
                proofFile: $request->file('proof_file'),
            );

            return redirect()->route('booking.show', $booking->id)
                ->with('toast_success', 'Booking berhasil dibuat! Kode booking: ' . $booking->booking_code);
        } catch (\App\Exceptions\SlotPenuhException $e) {
            return back()->with('toast_error', $e->getMessage())->withInput();
        } catch (\App\Exceptions\Booking\BookingFullException $e) {
            return back()->with('toast_error', $e->getMessage())->withInput();
        } catch (\App\Exceptions\Booking\BookingDuplicateException $e) {
            return back()->with('toast_error', $e->getMessage())->withInput();
        } catch (\App\Exceptions\Booking\BookingEmailUnverifiedException $e) {
            return redirect()->route('verification.notice')->with('toast_warning', $e->getMessage());
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