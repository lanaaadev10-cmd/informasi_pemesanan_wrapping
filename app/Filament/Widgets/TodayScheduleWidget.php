<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayScheduleWidget extends BaseWidget
{
    protected static ?string $heading = 'Jadwal Pengerjaan Hari Ini';
    protected static ?string $description = 'Antrean pengerjaan unit mobil di studio hari ini';
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::with(['layanan', 'user'])
                    ->whereDate('booking_date', now()->toDateString())
                    ->orderBy('booking_time', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_time')
                    ->label('Jam')
                    ->formatStateUsing(fn (?string $state) => $state ? substr($state, 0, 5) . ' WIB' : '09:00 WIB')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('pelanggan_nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn (Booking $record) => ($record->vehicle_name ?? 'Mobil') . ($record->vehicle_license ? ' • ' . $record->vehicle_license : '')),

                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Paket Layanan')
                    ->limit(18),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? (BookingStatus::tryFrom($state)?->label() ?? $state) : '-')
                    ->color(fn (?string $state): string => $state ? (BookingStatus::tryFrom($state)?->badgeColor() ?? 'gray') : 'gray'),
            ])
            ->actions([
                Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Booking $record) => $record->whatsapp_notification_url)
                    ->openUrlInNewTab(),

                Action::make('view')
                    ->label('Buka')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Booking $record): string => BookingResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('Tidak Ada Jadwal Pengerjaan Hari Ini')
            ->emptyStateDescription('Seluruh kuota hari ini masih bebas untuk pengerjaan baru.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->paginated(false);
    }
}
