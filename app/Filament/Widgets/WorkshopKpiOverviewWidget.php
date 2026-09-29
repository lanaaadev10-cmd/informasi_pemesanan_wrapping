<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Pesanan;
use App\Services\SlotKuotaService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WorkshopKpiOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 4,
        ];
    }

    protected function getStats(): array
    {
        // 1. Total Pendapatan
        $bookingRevenue = (float) BookingPayment::whereIn('status', ['verified', 'approved'])->sum('amount');
        $pesananRevenue = (float) Pesanan::whereIn('status', ['dikonfirmasi', 'sedang_diproses', 'selesai'])->sum('total_harga');
        $totalPendapatan = $bookingRevenue + $pesananRevenue;

        // 2. Kuota Hari Ini
        $quota = app(SlotKuotaService::class)->getTodayQuota();
        $available = $quota['available'];
        $max = $quota['max'];
        $booked = max(0, $max - $available);

        // 3. Perlu Verifikasi & Tindakan
        $pendingBooking = Booking::whereIn('status', [
            BookingStatus::PENDING->value,
            BookingStatus::PAYMENT_UPLOADED->value,
        ])->count();

        $pendingPesanan = Pesanan::whereIn('status', [
            Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
            Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
        ])->count();

        $totalPending = $pendingBooking + $pendingPesanan;

        // 4. Mobil Aktif / Sedang Dikerjakan
        $inProgressBooking = Booking::whereIn('status', [
            BookingStatus::CONFIRMED->value,
            BookingStatus::IN_PROGRESS->value,
        ])->count();

        $inProgressPesanan = Pesanan::where('status', Pesanan::STATUS_SEDANG_DIPROSES)->count();
        $totalAktif = $inProgressBooking + $inProgressPesanan;

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Akumulasi pesanan & DP booking')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->url(route('filament.admin.pages.laporan-penjualan')),

            Stat::make('Slot Kuota Hari Ini', "{$booked} / {$max} Slot Terisi")
                ->description($quota['is_full'] ? 'Workshop Penuh (5/5)' : "Sisa {$available} slot dapat dipesan")
                ->descriptionIcon($quota['is_full'] ? 'heroicon-m-lock-closed' : 'heroicon-m-calendar-days')
                ->color($quota['is_full'] ? 'danger' : ($available <= 1 ? 'warning' : 'primary'))
                ->url(route('filament.admin.pages.kalender-booking')),

            Stat::make('Perlu Verifikasi', "{$totalPending} Transaksi")
                ->description($totalPending > 0 ? 'Menunggu konfirmasi / bukti bayar' : 'Semua transaksi terverifikasi')
                ->descriptionIcon($totalPending > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-badge')
                ->color($totalPending > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.booking.index', ['activeTab' => 'payment'])),

            Stat::make('Mobil Dikerjakan', "{$totalAktif} Unit")
                ->description('Sedang diproses di workshop')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info')
                ->url(route('filament.admin.resources.pesanans.index', ['activeTab' => 'proses'])),
        ];
    }
}
