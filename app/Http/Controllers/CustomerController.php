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

        return view('dashboard.customer.dashboard.index', compact(
            'layanans', 'galeris', 'latestOrder', 'latestOrders',
            'ratingSummary', 'upcomingBookings'
        ));

    }
}