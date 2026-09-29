<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\SlotKuotaService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $todayBookings = Booking::whereDate('booking_date', $today)->count();
        $monthBookings = Booking::whereBetween('booking_date', [$startOfMonth, $endOfMonth])->count();

        // Kuota slot hari ini
        $quota = app(SlotKuotaService::class)->getTodayQuota();
        $availableToday = $quota['available'];
        $maxSlot = $quota['max'];

        // Status counts
        $pendingCount = Booking::where('status', BookingStatus::PENDING->value)->count();
        $confirmedCount = Booking::whereIn('status', [BookingStatus::CONFIRMED->value, BookingStatus::AWAITING_PAYMENT->value, BookingStatus::APPROVED->value])->count();
        $completedCount = Booking::where('status', BookingStatus::COMPLETED->value)->count();
        $cancelledCount = Booking::whereIn('status', [BookingStatus::CANCELLED->value, BookingStatus::REJECTED->value])->count();

        return [
            Stat::make('Booking Hari Ini', $todayBookings)
                ->description('Total jadwal booking hari ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Booking Bulan Ini', $monthBookings)
                ->description('Akumulasi jadwal bulan ' . now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Slot Tersedia Hari Ini', "{$availableToday} / {$maxSlot} Slot")
                ->description($quota['is_full'] ? 'Kuota hari ini PENUH (5/5)' : "Sisa {$availableToday} slot dapat dipesan")
                ->descriptionIcon($quota['is_full'] ? 'heroicon-m-lock-closed' : 'heroicon-m-check-badge')
                ->color($quota['is_full'] ? 'danger' : ($availableToday <= 1 ? 'warning' : 'success')),

            Stat::make('Menunggu Konfirmasi', $pendingCount)
                ->description('Booking baru belum diverifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Dikonfirmasi / Berjalan', $confirmedCount)
                ->description('Booking aktif disetujui')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Booking Selesai', $completedCount)
                ->description('Pengerjaan wrapping rampung')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),

            Stat::make('Booking Dibatalkan', $cancelledCount)
                ->description('Dibatalkan / kuota dilepas')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
