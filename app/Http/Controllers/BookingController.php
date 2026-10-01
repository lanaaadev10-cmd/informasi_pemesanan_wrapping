<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Layanan;
use App\Services\BookingService;
use App\Enums\PaymentType;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UploadBuktiBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Settings\CompanySettings;

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

        // Ambil ID layanan dari query parameter (dari katalog / keranjang)
        $selectedLayananId = $request->get('layanan_id');
        if (!$selectedLayananId && Auth::check()) {
            $activeCart = \App\Models\Keranjang::where('id_user', Auth::id())
                ->where('status', 'active')
                ->with('details')
                ->first();
            if ($activeCart && $activeCart->details->isNotEmpty()) {
                $selectedLayananId = $activeCart->details->first()->id_paket;
            }
        }

        return view('customer.booking.create', compact('layanans', 'selectedDate', 'todayQuota', 'user', 'selectedLayananId'));
    }

    /**
     * Proses simpan booking (dengan anti-overbooking di service).
     */
    public function store(StoreBookingRequest $request)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('toast_warning', 'Email Anda belum terverifikasi. Verifikasi email terlebih dahulu sebelum mengajukan booking.');
        }

        $data = $request->validated();

        try {
            $booking = $this->bookingService->createBooking(
                user: Auth::user(),
                data: $data,
                proofFile: $request->file('proof_file'),
            );

            // Tandai keranjang aktif user sebagai checked_out jika ada
            if (Auth::check()) {
                $activeCart = \App\Models\Keranjang::where('id_user', Auth::id())
                    ->where('status', 'active')
                    ->first();
                if ($activeCart) {
                    $activeCart->update(['status' => 'checked_out']);
                }
            }

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
        $query = Booking::with(['user', 'layanan', 'payment', 'rating'])
            ->where('id', $id);

        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $booking = $query->firstOrFail();

        return view('customer.booking.show', compact('booking'));
    }

    /**
     * Unduh Invoice PDF Resmi untuk Booking Wrapping.
     * Menggunakan DomPDF untuk menghasilkan PDF langsung tanpa window.print().
     */
    public function invoice($id)
    {
        $query = Booking::with(['user', 'layanan', 'payment'])
            ->where('id', $id);

        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $booking = $query->firstOrFail();

        $statusVal = $booking->status instanceof \App\Enums\BookingStatus
            ? $booking->status->value
            : (string) $booking->status;

        $allowedStatuses = ['approved', 'in_progress', 'completed', 'confirmed', 'payment_uploaded'];
        if (!in_array($statusVal, $allowedStatuses) && !Auth::user()->hasRole('admin')) {
            return back()->with('toast_error', 'Invoice belum dapat diunduh. Tunggu verifikasi pembayaran.');
        }

        $profil = app(CompanySettings::class);

        $pdf = Pdf::loadView('customer.booking.invoice-pdf', compact('booking', 'profil'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'     => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'dpi'             => 96,
            ]);

        $filename = 'Invoice-' . $booking->booking_code . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Upload bukti transfer (status: awaiting_payment -> payment_uploaded).
     */
    public function uploadBukti(UploadBuktiBookingRequest $request, $id)
    {

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