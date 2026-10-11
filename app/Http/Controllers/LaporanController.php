<?php

namespace App\Http\Controllers;

use App\Models\BookingPayment;
use App\Models\Pesanan;
use App\Settings\CompanySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()?->can('laporan view')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat laporan.');
        }

        $type = $request->get('type', 'hari');
        $now = Carbon::now();

        // Tentukan rentang waktu berdasarkan periode
        if ($type === 'hari') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            $title = 'Harian ('.$now->format('d M Y').')';
        } elseif ($type === 'minggu') {
            $startDate = Carbon::now()->startOfWeek()->startOfDay();
            $endDate = Carbon::now()->endOfWeek()->endOfDay();
            $title = 'Mingguan ('.Carbon::now()->startOfWeek()->format('d M').' - '.Carbon::now()->endOfWeek()->format('d M Y').')';
        } elseif ($type === 'bulan') {
            $startDate = Carbon::now()->startOfMonth()->startOfDay();
            $endDate = Carbon::now()->endOfMonth()->endOfDay();
            $title = 'Bulanan ('.$now->format('F Y').')';
        } elseif ($type === 'custom' || ($request->filled('start_date') && $request->filled('end_date'))) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $title = 'Periode ('.$startDate->format('d M Y').' s/d '.$endDate->format('d M Y').')';
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            $title = 'Harian ('.$now->format('d M Y').')';
        }

        // 1. Ambil data Pesanan (dikonfirmasi / sedang diproses / selesai)
        $pesananItems = Pesanan::with(['user', 'details.layanan'])
            ->whereIn('status', [
                Pesanan::STATUS_DIKONFIRMASI,
                Pesanan::STATUS_SEDANG_DIPROSES,
                Pesanan::STATUS_SELESAI,
                'selesai',
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                $layananNames = $item->details->map(fn ($d) => $d->layanan?->nama_layanan)->filter()->join(', ');

                return (object) [
                    'id' => $item->id_pesanan,
                    'kode_transaksi' => $item->kode_pesanan,
                    'kode_pesanan' => $item->kode_pesanan,
                    'tipe_transaksi' => $item->order_source === 'offline' ? 'Pesanan Walk-In' : 'Pesanan Online',
                    'customer_name' => $item->customer_name ?? ($item->user?->name ?? 'Pelanggan Umum'),
                    'layanan_nama' => $layananNames ?: 'Layanan Wrapping',
                    'created_at' => $item->created_at,
                    'status' => $item->status,
                    'total_harga' => (float) $item->total_harga,
                ];
            });

        // 2. Ambil data Booking Payment yang telah terverifikasi
        $bookingItems = BookingPayment::with(['booking.user', 'booking.layanan'])
            ->whereIn('status', ['verified', 'approved'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($payment) {
                $booking = $payment->booking;
                $code = $booking?->booking_code ?? ('BKG-'.$payment->booking_id);
                $custName = $booking?->customer_name ?? ($booking?->user?->name ?? 'Pelanggan Umum');
                $layananName = $booking?->layanan?->nama_layanan ?? 'Layanan Wrapping';
                $statusLabel = $booking?->status instanceof \App\Enums\BookingStatus
                    ? $booking->status->label()
                    : ($booking?->status ?? 'Terverifikasi');

                return (object) [
                    'id' => $booking?->id ?? $payment->id,
                    'kode_transaksi' => $code,
                    'kode_pesanan' => $code,
                    'tipe_transaksi' => 'Booking Online ('.strtoupper($booking?->payment_type ?? 'DP').')',
                    'customer_name' => $custName,
                    'layanan_nama' => $layananName,
                    'created_at' => $payment->created_at,
                    'status' => $statusLabel,
                    'total_harga' => (float) $payment->amount,
                ];
            });

        // 3. Gabungkan seluruh transaksi secara kronologis
        $semuaTransaksi = $pesananItems->concat($bookingItems)->sortByDesc('created_at')->values();
        $totalPendapatan = $semuaTransaksi->sum('total_harga');
        $company = app(CompanySettings::class);

        return view('admin.laporan.print', [
            'pesanans' => $semuaTransaksi,
            'transaksis' => $semuaTransaksi,
            'title' => $title,
            'totalPendapatan' => $totalPendapatan,
            'type' => $type,
            'company' => $company,
            'totalPesananCount' => $pesananItems->count(),
            'totalBookingCount' => $bookingItems->count(),
        ]);
    }
}
