<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pesanan;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Hub Transaksi Terpadu Customer Dashboard (Fase 1).
 * Menggabungkan riwayat jadwal Booking dan Pesanan dalam satu antarmuka terintegrasi.
 */
class TransaksiController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    /**
     * Menampilkan daftar transaksi terpadu (Jadwal Booking & Pesanan).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $type = $request->query('type', 'booking');
        if (!in_array($type, ['booking', 'pesanan'])) {
            $type = 'booking';
        }

        // Hitung notifikasi count / badge count untuk setiap tab
        $activeBookingsCount = Booking::where('user_id', $userId)
            ->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
            ->count();

        $activePesanansCount = Pesanan::where('id_user', $userId)
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->count();

        // 1. Data Booking
        $bookingStatus = $request->query('booking_status');
        $bookingTab = $request->query('booking_tab', $bookingStatus ? null : 'all');

        $bookings = $this->bookingService->getUserBookings(
            userId: $userId,
            perPage: 8,
            status: $bookingStatus,
            tab: $bookingTab,
        );

        $bookingStats = $this->bookingService->getUserBookingStats($userId);

        // 2. Data Pesanan (Eager loading anti N+1)
        $pesananStatus = $request->query('pesanan_status');
        $pesananQuery = Pesanan::where('id_user', $userId)
            ->with(['form', 'pembayaran', 'details.layanan'])
            ->latest();

        if ($pesananStatus === 'menunggu_pembayaran') {
            $pesananQuery->whereIn('status', [
                Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
                Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
                Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
            ]);
        } elseif ($pesananStatus === 'berjalan') {
            $pesananQuery->whereIn('status', [
                Pesanan::STATUS_DIKONFIRMASI,
                Pesanan::STATUS_SEDANG_DIPROSES,
            ]);
        } elseif ($pesananStatus === 'selesai') {
            $pesananQuery->where('status', Pesanan::STATUS_SELESAI);
        }

        $pesanans = $pesananQuery->paginate(8, ['*'], 'pesanan_page');

        return view('dashboard.customer.transaksi.index', compact(
            'type',
            'bookings',
            'bookingStats',
            'bookingTab',
            'bookingStatus',
            'pesanans',
            'pesananStatus',
            'activeBookingsCount',
            'activePesanansCount'
        ));
    }
}
