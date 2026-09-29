<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $bookingRevenue = (float) \App\Models\BookingPayment::whereIn('status', ['verified', 'approved'])->sum('amount');
        $pesananRevenue = (float) Pesanan::whereIn('status', ['dikonfirmasi', 'sedang_diproses', 'selesai'])->sum('total_harga');
        $totalPendapatan = $pesananRevenue + $bookingRevenue;

        $totalTransaksi = Pesanan::count() + \App\Models\Booking::count();
        $perluVerifikasi = Pesanan::whereIn('status', [Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN, Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN])->count()
            + \App\Models\Booking::whereIn('status', [\App\Enums\BookingStatus::PENDING->value, \App\Enums\BookingStatus::PAYMENT_UPLOADED->value])->count();
        $totalSelesai = Pesanan::where('status', 'selesai')->count()
            + \App\Models\Booking::where('status', \App\Enums\BookingStatus::COMPLETED->value)->count();

        return [
            Stat::make('Total Transaksi', $totalTransaksi)
                ->description('Gabungan pesanan online & booking')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Akumulasi pesanan & DP booking')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Perlu Verifikasi', $perluVerifikasi)
                ->description('Pesanan / bukti bayar baru masuk')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),

            Stat::make('Pengerjaan Selesai', $totalSelesai)
                ->description('Total wrapping tuntas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
