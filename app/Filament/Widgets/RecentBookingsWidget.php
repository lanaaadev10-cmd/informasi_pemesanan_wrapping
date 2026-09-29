<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Daftar Booking Terbaru';
    protected static ?string $description = 'Pemesanan jadwal online terbaru dari pelanggan';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Booking::with('layanan', 'user')->latest()->limit(8))
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
                    ->formatStateUsing(fn (?string $state): string => $state ? (BookingStatus::tryFrom($state)?->label() ?? $state) : '-')
                    ->color(fn (?string $state): string => $state ? (BookingStatus::tryFrom($state)?->badgeColor() ?? 'gray') : 'gray')
                    ->icon(fn (?string $state): string => $state
                        ? match ($state) {
                            'pending' => 'heroicon-m-clock',
                            'confirmed' => 'heroicon-m-check-badge',
                            'awaiting_payment' => 'heroicon-m-credit-card',
                            'payment_uploaded' => 'heroicon-m-magnifying-glass',
                            'approved' => 'heroicon-m-check-circle',
                            'in_progress' => 'heroicon-m-wrench-screwdriver',
                            'completed' => 'heroicon-m-check-circle',
                            default => 'heroicon-m-x-circle',
                        }
                        : 'heroicon-m-question-mark-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dipesan')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Booking $record) => $record->whatsapp_notification_url)
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('view')
                    ->label('Buka')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Booking $record): string => BookingResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
