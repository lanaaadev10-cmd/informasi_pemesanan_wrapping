<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public function getSubheading(): ?string
    {
        $admin = auth()->user();
        return 'Selamat datang, ' . ($admin?->name ?? 'Admin') . '! Pantau kapasitas workshop, antrean jadwal, dan transaksi pengerjaan hari ini.';
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('createBooking')
                ->label('Booking Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(route('filament.admin.resources.booking.create')),

            Action::make('createPesanan')
                ->label('Pesanan Walk-In')
                ->icon('heroicon-o-shopping-bag')
                ->color('gray')
                ->url(route('filament.admin.resources.pesanans.create')),

            Action::make('kalenderKuota')
                ->label('Kalender Kuota')
                ->icon('heroicon-o-calendar-days')
                ->color('gray')
                ->url(route('filament.admin.pages.kalender-booking')),
        ];
    }
}
