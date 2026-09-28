<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Satu query agregat menggantikan 5 query terpisah per tab.
        $counts = Booking::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $sum = fn (array $statuses): int => collect($statuses)->sum(
            fn (string $status): int => (int) ($counts[$status] ?? 0)
        );

        return [
            'all' => \Filament\Schemas\Components\Tabs\Tab::make('Semua Booking'),
            'pending' => \Filament\Schemas\Components\Tabs\Tab::make('Menunggu Konfirmasi')
                ->modifyQueryUsing(fn ($query) => $query->where('status', BookingStatus::PENDING->value))
                ->badge($counts[BookingStatus::PENDING->value] ?? 0)
                ->badgeColor('warning'),
            'payment' => \Filament\Schemas\Components\Tabs\Tab::make('Verifikasi Pembayaran')
                ->modifyQueryUsing(fn ($query) => $query->where('status', BookingStatus::PAYMENT_UPLOADED->value))
                ->badge($counts[BookingStatus::PAYMENT_UPLOADED->value] ?? 0)
                ->badgeColor('danger'),
            'proses' => \Filament\Schemas\Components\Tabs\Tab::make('Sedang Berjalan')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [
                    BookingStatus::AWAITING_PAYMENT->value,
                    BookingStatus::APPROVED->value,
                    BookingStatus::IN_PROGRESS->value,
                ]))
                ->badge($sum([
                    BookingStatus::AWAITING_PAYMENT->value,
                    BookingStatus::APPROVED->value,
                    BookingStatus::IN_PROGRESS->value,
                ]))
                ->badgeColor('info'),
            'selesai' => \Filament\Schemas\Components\Tabs\Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', BookingStatus::COMPLETED->value))
                ->badge($counts[BookingStatus::COMPLETED->value] ?? 0),
            'batal' => \Filament\Schemas\Components\Tabs\Tab::make('Ditolak/Dibatalkan')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [
                    BookingStatus::REJECTED->value,
                    BookingStatus::CANCELLED->value,
                ]))
                ->badge($sum([
                    BookingStatus::REJECTED->value,
                    BookingStatus::CANCELLED->value,
                ]))
                ->badgeColor('danger'),
        ];
    }
}