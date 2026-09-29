<?php

namespace App\Filament\Pages;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BlockedDate;
use App\Services\SlotKuotaService;
use Filament\Actions\Action;
use Filament\Pages\Page;

class KalenderBooking extends Page
{
    protected static ?string $navigationLabel = 'Kalender Kuota';
    protected static ?string $title = 'Kalender Kuota & Jadwal Booking';
    protected static \UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'kalender-booking';

    protected string $view = 'filament.pages.kalender-booking';

    public function getHeaderActions(): array
    {
        return [
            Action::make('createBooking')
                ->label('Tambah Booking Manual')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(fn () => route('filament.admin.resources.booking.create')),

            Action::make('blockedDates')
                ->label('Kelola Tanggal Libur')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->url(fn () => route('filament.admin.resources.tanggal-diblokir.index')),
        ];
    }

    protected function getViewData(): array
    {
        $today = now()->toDateString();
        $quotaService = app(SlotKuotaService::class);
        $todayQuota = $quotaService->getTodayQuota();

        return [
            'todayQuota' => $todayQuota,
            'todayBookings' => Booking::with('layanan', 'user')
                ->whereDate('booking_date', $today)
                ->get(),
        ];
    }
}
