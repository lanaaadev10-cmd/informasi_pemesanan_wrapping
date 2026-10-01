<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Services\BookingService;

class CustomerController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function katalog()
    {
        $layanan = Layanan::all();
        $ratingSummary = TestimoniController::summaryPerLayananKeyed();

        return view('landing.katalog.index', compact('layanan', 'ratingSummary'));
    }

    public function dashboard()
    {
        $layanans      = Layanan::all();
        $galeris       = Galeri::all();
        $ratingSummary = \App\Http\Controllers\TestimoniController::summaryPerLayananKeyed();

        $latestOrders = \App\Models\Pesanan::where('id_user', auth()->id())
            ->with(['form', 'details.layanan'])
            ->latest()
            ->limit(5)
            ->get();

        $latestOrder = $latestOrders->first();

        // Widget kalender booking: 5 booking terbaru yang belum selesai/batal
        $upcomingBookings = Booking::with('layanan')
            ->where('user_id', auth()->id())
            ->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
            ->orderBy('booking_date')
            ->limit(5)
            ->get();

        // Booking terbaru yang selesai dan belum diberi ulasan (untuk Review Reminder Banner)
        $unreviewedBooking = Booking::with(['layanan', 'rating'])
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('rating')
            ->latest()
            ->first();

        // Pesanan terbaru yang selesai dan belum diberi ulasan
        $unreviewedPesanan = \App\Models\Pesanan::with(['details.layanan', 'ratings'])
            ->where('id_user', auth()->id())
            ->where('status', \App\Models\Pesanan::STATUS_SELESAI)
            ->whereDoesntHave('ratings')
            ->latest()
            ->first();

        $userId = auth()->id();

        // Jumlah tagihan yang belum lunas (Booking + Pesanan) untuk badge di 'Bayar Tagihan'
        $unpaidTagihanCount = Booking::where('user_id', $userId)
            ->whereIn('status', [
                \App\Enums\BookingStatus::AWAITING_PAYMENT->value,
                \App\Enums\BookingStatus::PAYMENT_UPLOADED->value,
            ])
            ->count() + \App\Models\Pesanan::where('id_user', $userId)
            ->whereIn('status', [
                \App\Models\Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
                \App\Models\Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
                \App\Models\Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
            ])
            ->count();

        // Jumlah booking aktif untuk badge di 'Jadwal Servis'
        $activeBookingsCount = Booking::where('user_id', $userId)
            ->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
            ->count();

        // Jumlah pengerjaan selesai yang belum diulas
        $unreviewedCount = ($unreviewedBooking ? 1 : 0) + ($unreviewedPesanan ? 1 : 0);

        return view('dashboard.customer.dashboard.index', compact(
            'layanans', 'galeris', 'latestOrder', 'latestOrders',
            'ratingSummary', 'upcomingBookings',
            'unreviewedBooking', 'unreviewedPesanan',
            'unpaidTagihanCount', 'activeBookingsCount', 'unreviewedCount'
        ));

    }
}