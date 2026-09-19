<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('layanan.nama_layanan')
                    ->label('Paket')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                TextColumn::make('booking_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn (Booking $record): string => $record->booking_date->isPast() ? 'gray' : 'info'),

                TextColumn::make('payment_type')
                    ->label('Tipe Bayar')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? PaymentType::from($state)->label() : '-')
                    ->color(fn (?string $state): string => $state === PaymentType::DP->value ? 'warning' : 'success'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? BookingStatus::from($state)->label() : '-')
                    ->color(fn (?string $state): string => $state ? BookingStatus::from($state)->badgeColor() : 'gray')
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

                ImageColumn::make('payment.proof_file')
                    ->label('Bukti Bayar')
                    ->square()
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(null),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(BookingStatus::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->toArray()),

                Filter::make('booking_date')
                    ->label('Tanggal Pengerjaan')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('booking_date'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['booking_date'] ?? null,
                                fn (\Illuminate\Database\Eloquent\Builder $q, string $date) => $q->whereDate('booking_date', $date)
                            );
                    }),
            ])
            ->actions([

                // Admin konfirmasi booking → awaiting_payment
                Action::make('confirmBooking')
                    ->label('Konfirmasi Booking')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatus::PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Booking')
                    ->modalDescription('Booking valid? Customer akan diminta mengunggah bukti pembayaran.')
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->action(function (Booking $record) {
                        app(BookingService::class)->confirmBooking($record);
                        \Filament\Notifications\Notification::make()
                            ->title('Booking dikonfirmasi!')
                            ->success()->send();
                    }),

                // Admin verifikasi pembayaran → approved
                Action::make('verifyPayment')
                    ->label('Verifikasi Pembayaran')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatus::PAYMENT_UPLOADED)
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran Booking')
                    ->modalDescription('Pastikan uang sudah masuk. Booking akan disetujui dan jadwal terkunci.')
                    ->modalSubmitActionLabel('Ya, Pembayaran Valid')
                    ->action(function (Booking $record) {
                        app(BookingService::class)->verifyPayment($record->payment);
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran diverifikasi dan booking disetujui!')
                            ->success()->send();
                    }),

                // Admin mulai kerjakan → in_progress
                Action::make('startProcessing')
                    ->label('Mulai Kerjakan')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('primary')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatus::APPROVED)
                    ->requiresConfirmation()
                    ->modalHeading('Mulai Pengerjaan')
                    ->modalSubmitActionLabel('Mulai Kerjakan')
                    ->action(function (Booking $record) {
                        app(BookingService::class)->startProcessing($record);
                        \Filament\Notifications\Notification::make()
                            ->title('Pengerjaan dimulai!')
                            ->success()->send();
                    }),

                // Admin selesaikan → completed
                Action::make('completeBooking')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatus::IN_PROGRESS)
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Booking')
                    ->modalSubmitActionLabel('Ya, Tandai Selesai')
                    ->action(function (Booking $record) {
                        app(BookingService::class)->completeBooking($record);
                        \Filament\Notifications\Notification::make()
                            ->title('Pengerjaan diselesaikan!')
                            ->success()->send();
                    }),

                // Admin tolak booking → rejected
                Action::make('rejectBooking')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Booking $record): bool => in_array($record->status, [
                        BookingStatus::PENDING,
                        BookingStatus::PAYMENT_UPLOADED,
                    ]))
                    ->form([
                        \Filament\Forms\Components\Textarea::make('alasan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Jelaskan alasan penolakan booking ini...'),
                    ])
                    ->action(function (Booking $record, array $data) {
                        app(BookingService::class)->rejectBooking($record, $data['alasan']);
                        \Filament\Notifications\Notification::make()
                            ->title('Booking ditolak.')
                            ->danger()->send();
                    }),

                Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Booking $record): string => BookingResource::getUrl('view', ['record' => $record])),

                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}