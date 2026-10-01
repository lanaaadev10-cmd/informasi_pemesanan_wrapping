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

                TextColumn::make('pelanggan_nama')
                    ->label('Pelanggan')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('customer_name', 'like', "%{$search}%")
                              ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->sortable(),

                TextColumn::make('pelanggan_phone')
                    ->label('WhatsApp')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('customer_phone', 'like', "%{$search}%")
                              ->orWhereHas('user', fn ($q) => $q->where('phone', 'like', "%{$search}%")->orWhere('no_hp', 'like', "%{$search}%"));
                    })
                    ->copyable(),

                TextColumn::make('layanan.nama_layanan')
                    ->label('Paket')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                TextColumn::make('booking_date')
                    ->label('Jadwal')
                    ->formatStateUsing(fn ($state, Booking $record) => ($record->booking_date ? $record->booking_date->format('d M Y') : '-') . ($record->booking_time ? ' • ' . $record->booking_time : ''))
                    ->sortable()
                    ->badge()
                    ->color(fn (Booking $record): string => $record->booking_date && $record->booking_date->isPast() ? 'gray' : 'info'),

                TextColumn::make('payment_type')
                    ->label('Tipe Bayar')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof PaymentType) {
                            return $state->label();
                        }
                        if (is_string($state) && $state !== '') {
                            return PaymentType::tryFrom($state)?->label() ?? $state;
                        }
                        return '-';
                    })
                    ->color(function ($state): string {
                        $val = $state instanceof PaymentType ? $state->value : $state;
                        return $val === PaymentType::DP->value ? 'warning' : 'success';
                    }),

                TextColumn::make('status')
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

                // Admin reschedule jadwal booking
                Action::make('reschedule')
                    ->label('Ubah Jadwal')
                    ->icon('heroicon-o-calendar')
                    ->color('info')
                    ->visible(fn (Booking $record): bool => !in_array($record->status, [BookingStatus::CANCELLED, BookingStatus::REJECTED, BookingStatus::COMPLETED]))
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('new_date')
                            ->label('Tanggal Baru')
                            ->required()
                            ->minDate(now()->toDateString())
                            ->default(fn (Booking $record) => $record->booking_date?->format('Y-m-d')),
                        \Filament\Forms\Components\TextInput::make('new_time')
                            ->label('Jam Baru')
                            ->placeholder('09:00')
                            ->default(fn (Booking $record) => $record->booking_time ?: '09:00'),
                        \Filament\Forms\Components\TextInput::make('reason')
                            ->label('Catatan Alasan Reschedule')
                            ->placeholder('Permintaan pelanggan / penyesuaian teknisi'),
                        \Filament\Forms\Components\Toggle::make('override_quota')
                            ->label('Override Kuota Maksimal 5 Slot')
                            ->helperText('Aktifkan jika ada izin khusus mengabaikan kuota harian.'),
                    ])
                    ->action(function (Booking $record, array $data) {
                        try {
                            app(BookingService::class)->rescheduleBooking(
                                $record,
                                $data['new_date'],
                                $data['new_time'] ?? null,
                                $data['reason'] ?? null,
                                (bool) ($data['override_quota'] ?? false)
                            );
                            \Filament\Notifications\Notification::make()
                                ->title('Jadwal booking berhasil diperbarui!')
                                ->success()->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal ubah jadwal')
                                ->body($e->getMessage())
                                ->danger()->send();
                        }
                    }),

                // Admin batalkan booking (mengembalikan kuota)
                Action::make('cancelBooking')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Booking $record): bool => !in_array($record->status, [BookingStatus::CANCELLED, BookingStatus::REJECTED, BookingStatus::COMPLETED]))
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Booking')
                    ->modalDescription('Slot kuota pada tanggal ini akan otomatis kembali tersedia untuk pelanggan lain.')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('alasan')
                            ->label('Alasan Pembatalan')
                            ->required()
                            ->placeholder('Masukkan alasan pembatalan booking...'),
                    ])
                    ->action(function (Booking $record, array $data) {
                        try {
                            app(BookingService::class)->cancelBooking($record, $data['alasan']);
                            \Filament\Notifications\Notification::make()
                                ->title('Booking berhasil dibatalkan dan kuota slot dikembalikan!')
                                ->success()->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal membatalkan')
                                ->body($e->getMessage())
                                ->danger()->send();
                        }
                    }),

                // Tombol WhatsApp
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Booking $record) => $record->whatsapp_notification_url)
                    ->openUrlInNewTab(),

                Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Booking $record): string => BookingResource::getUrl('view', ['record' => $record])),

                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}