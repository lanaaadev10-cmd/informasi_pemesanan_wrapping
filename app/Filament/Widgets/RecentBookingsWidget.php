<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Booking Online Terbaru';
    protected static ?string $description = '5 booking online paling baru';
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(Booking::with('layanan', 'user')->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('pelanggan_nama')
                    ->label('Nama Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pelanggan_phone')
                    ->label('WhatsApp')
                    ->copyable(),

                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Paket Layanan')
                    ->limit(22),

                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Jadwal & Jam')
                    ->formatStateUsing(fn ($state, Booking $record) => ($record->booking_date ? $record->booking_date->format('d M Y') : '-') . ($record->booking_time ? ' • ' . $record->booking_time : ''))
                    ->badge()
                    ->color(fn (Booking $record): string => $record->booking_date && $record->booking_date->isPast() ? 'gray' : 'info'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        $status = $state instanceof BookingStatus ? $state : ($state ? BookingStatus::tryFrom((string) $state) : null);
                        return $status?->label() ?? (is_string($state) && $state !== '' ? $state : '-');
                    })
                    ->color(function ($state): string {
                        $status = $state instanceof BookingStatus ? $state : ($state ? BookingStatus::tryFrom((string) $state) : null);
                        return $status?->badgeColor() ?? 'gray';
                    })
                    ->icon(function ($state): string {
                        $status = $state instanceof BookingStatus ? $state : ($state ? BookingStatus::tryFrom((string) $state) : null);
                        return $status?->icon() ?? 'heroicon-m-question-mark-circle';
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dipesan')
                    ->since(),
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
            ->paginated(false);
    }
}
