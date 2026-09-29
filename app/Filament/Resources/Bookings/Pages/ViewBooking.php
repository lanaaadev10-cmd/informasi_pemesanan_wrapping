<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Enums\BookingStatus;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Konfirmasi booking (PENDING → AWAITING_PAYMENT)
            Action::make('confirmBooking')
                ->label('Konfirmasi Booking')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === BookingStatus::PENDING)
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Booking')
                ->modalDescription('Booking valid? Customer akan diminta mengunggah bukti pembayaran.')
                ->modalSubmitActionLabel('Ya, Konfirmasi')
                ->action(function () {
                    try {
                        app(BookingService::class)->confirmBooking($this->record);
                        Notification::make()->title('Booking dikonfirmasi!')->success()->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()->title('Gagal konfirmasi')->body($e->getMessage())->danger()->send();
                    }
                }),

            // Verifikasi pembayaran (PAYMENT_UPLOADED → APPROVED)
            Action::make('verifyPayment')
                ->label('Verifikasi Pembayaran')
                ->icon('heroicon-o-banknotes')
                ->color('warning')
                ->visible(fn (): bool => $this->record->status === BookingStatus::PAYMENT_UPLOADED)
                ->requiresConfirmation()
                ->modalHeading('Verifikasi Pembayaran Booking')
                ->modalDescription('Pastikan uang sudah masuk. Booking akan disetujui dan jadwal terkunci.')
                ->modalSubmitActionLabel('Ya, Pembayaran Valid')
                ->action(function () {
                    try {
                        $this->record->loadMissing('payment');
                        if (! $this->record->payment) {
                            Notification::make()->title('Data pembayaran tidak ditemukan.')->danger()->send();
                            return;
                        }
                        app(BookingService::class)->verifyPayment($this->record->payment);
                        Notification::make()->title('Pembayaran diverifikasi dan booking disetujui!')->success()->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()->title('Gagal verifikasi')->body($e->getMessage())->danger()->send();
                    }
                }),

            // Mulai kerjakan (APPROVED → IN_PROGRESS)
            Action::make('startProcessing')
                ->label('Mulai Kerjakan')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('primary')
                ->visible(fn (): bool => $this->record->status === BookingStatus::APPROVED)
                ->requiresConfirmation()
                ->modalHeading('Mulai Pengerjaan')
                ->modalSubmitActionLabel('Mulai Kerjakan')
                ->action(function () {
                    try {
                        app(BookingService::class)->startProcessing($this->record);
                        Notification::make()->title('Pengerjaan dimulai!')->success()->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()->title('Gagal')->body($e->getMessage())->danger()->send();
                    }
                }),

            // Selesaikan (IN_PROGRESS → COMPLETED)
            Action::make('completeBooking')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === BookingStatus::IN_PROGRESS)
                ->requiresConfirmation()
                ->modalHeading('Selesaikan Booking')
                ->modalSubmitActionLabel('Ya, Tandai Selesai')
                ->action(function () {
                    try {
                        app(BookingService::class)->completeBooking($this->record);
                        Notification::make()->title('Pengerjaan diselesaikan!')->success()->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()->title('Gagal')->body($e->getMessage())->danger()->send();
                    }
                }),

            // Tolak (PENDING | PAYMENT_UPLOADED → REJECTED)
            Action::make('rejectBooking')
                ->label('Tolak Booking')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => in_array($this->record->status, [
                    BookingStatus::PENDING,
                    BookingStatus::PAYMENT_UPLOADED,
                ]))
                ->form([
                    \Filament\Forms\Components\Textarea::make('alasan')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->placeholder('Jelaskan alasan penolakan booking ini...'),
                ])
                ->action(function (array $data) {
                    try {
                        app(BookingService::class)->rejectBooking($this->record, $data['alasan']);
                        Notification::make()->title('Booking ditolak.')->danger()->send();
                        $this->refreshFormData(['status']);
                    } catch (\Exception $e) {
                        Notification::make()->title('Gagal menolak')->body($e->getMessage())->danger()->send();
                    }
                }),

            // Reschedule jadwal
            Action::make('reschedule')
                ->label('Ubah Jadwal')
                ->icon('heroicon-o-calendar')
                ->color('info')
                ->visible(fn (): bool => ! in_array($this->record->status, [
                    BookingStatus::CANCELLED,
                    BookingStatus::REJECTED,
                    BookingStatus::COMPLETED,
                ]))
                ->form([
                    \Filament\Forms\Components\DatePicker::make('new_date')
                        ->label('Tanggal Baru')
                        ->required()
                        ->minDate(now()->toDateString())
                        ->default(fn () => $this->record->booking_date?->format('Y-m-d')),
                    \Filament\Forms\Components\TextInput::make('new_time')
                        ->label('Jam Baru')
                        ->placeholder('09:00')
                        ->default(fn () => $this->record->booking_time ?: '09:00'),
                    \Filament\Forms\Components\TextInput::make('reason')
                        ->label('Catatan Alasan Reschedule')
                        ->placeholder('Permintaan pelanggan / penyesuaian teknisi'),
                    \Filament\Forms\Components\Toggle::make('override_quota')
                        ->label('Override Kuota Maksimal 5 Slot')
                        ->helperText('Aktifkan jika ada izin khusus mengabaikan kuota harian.'),
                ])
                ->action(function (array $data) {
                    try {
                        app(BookingService::class)->rescheduleBooking(
                            $this->record,
                            $data['new_date'],
                            $data['new_time'] ?? null,
                            $data['reason'] ?? null,
                            (bool) ($data['override_quota'] ?? false)
                        );
                        Notification::make()->title('Jadwal booking berhasil diperbarui!')->success()->send();
                        $this->refreshFormData(['booking_date', 'booking_time']);
                    } catch (\Exception $e) {
                        Notification::make()->title('Gagal ubah jadwal')->body($e->getMessage())->danger()->send();
                    }
                }),

            // Kirim notifikasi WhatsApp ke pelanggan
            Action::make('whatsapp')
                ->label('WhatsApp Pelanggan')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(fn (): string => $this->record->whatsapp_notification_url)
                ->openUrlInNewTab(),

            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}