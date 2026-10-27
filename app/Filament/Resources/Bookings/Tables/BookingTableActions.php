<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enums\BookingStatus;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Reservasi;
use App\Services\BookingService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;

/**
 * BookingTableActions — Kumpulan Aksi Operasional Tabel Booking Filament
 */
class BookingTableActions
{
    /**
     * Dapatkan daftar seluruh aksi baris tabel booking.
     */
    public static function all(): array
    {
        return [
            self::confirmAction(),
            self::verifyPaymentAction(),
            self::startProcessingAction(),
            self::completeBookingAction(),
            self::rejectBookingAction(),
            self::rescheduleAction(),
            self::cancelBookingAction(),
            self::approveMaterialAction(),
            self::rejectMaterialAction(),
            self::sendQuotationAction(),
            self::whatsappAction(),
            self::viewDetailAction(),
            DeleteAction::make(),
        ];
    }

    public static function confirmAction(): Action
    {
        return Action::make('confirmBooking')
            ->label('Konfirmasi Booking')
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->visible(fn (Reservasi $record): bool => $record->status === BookingStatus::PENDING)
            ->requiresConfirmation()
            ->modalHeading('Konfirmasi Booking')
            ->modalDescription('Booking valid? Customer akan diminta mengunggah bukti pembayaran.')
            ->modalSubmitActionLabel('Ya, Konfirmasi')
            ->action(function (Reservasi $record) {
                app(BookingService::class)->confirmBooking($record);
                Notification::make()->title('Booking dikonfirmasi!')->success()->send();
            });
    }

    public static function verifyPaymentAction(): Action
    {
        return Action::make('verifyPayment')
            ->label('Verifikasi Pembayaran')
            ->icon('heroicon-o-banknotes')
            ->color('warning')
            ->visible(fn (Reservasi $record): bool => $record->status === BookingStatus::PAYMENT_UPLOADED)
            ->requiresConfirmation()
            ->modalHeading('Verifikasi Pembayaran Booking')
            ->modalDescription('Pastikan uang sudah masuk. Booking akan disetujui dan jadwal terkunci.')
            ->modalSubmitActionLabel('Ya, Pembayaran Valid')
            ->action(function (Reservasi $record) {
                app(BookingService::class)->verifyPayment($record->payment);
                Notification::make()->title('Pembayaran diverifikasi dan booking disetujui!')->success()->send();
            });
    }

    public static function startProcessingAction(): Action
    {
        return Action::make('startProcessing')
            ->label('Mulai Kerjakan')
            ->icon('heroicon-o-wrench-screwdriver')
            ->color('primary')
            ->visible(fn (Reservasi $record): bool => $record->status === BookingStatus::APPROVED)
            ->requiresConfirmation()
            ->modalHeading('Mulai Pengerjaan')
            ->modalSubmitActionLabel('Mulai Kerjakan')
            ->action(function (Reservasi $record) {
                app(BookingService::class)->startProcessing($record);
                Notification::make()->title('Pengerjaan dimulai!')->success()->send();
            });
    }

    public static function completeBookingAction(): Action
    {
        return Action::make('completeBooking')
            ->label('Selesaikan')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->visible(fn (Reservasi $record): bool => $record->status === BookingStatus::IN_PROGRESS)
            ->requiresConfirmation()
            ->modalHeading('Selesaikan Booking')
            ->modalSubmitActionLabel('Ya, Tandai Selesai')
            ->action(function (Reservasi $record) {
                app(BookingService::class)->completeBooking($record);
                Notification::make()->title('Pengerjaan diselesaikan!')->success()->send();
            });
    }

    public static function rejectBookingAction(): Action
    {
        return Action::make('rejectBooking')
            ->label('Tolak')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->visible(fn (Reservasi $record): bool => in_array($record->status, [
                BookingStatus::PENDING,
                BookingStatus::PAYMENT_UPLOADED,
            ]))
            ->form([
                Textarea::make('alasan')
                    ->label('Alasan Penolakan')
                    ->required()
                    ->placeholder('Jelaskan alasan penolakan booking ini...'),
            ])
            ->action(function (Reservasi $record, array $data) {
                app(BookingService::class)->rejectBooking($record, $data['alasan']);
                Notification::make()->title('Booking ditolak.')->danger()->send();
            });
    }

    public static function rescheduleAction(): Action
    {
        return Action::make('reschedule')
            ->label('Ubah Jadwal')
            ->icon('heroicon-o-calendar')
            ->color('info')
            ->visible(fn (Reservasi $record): bool => !in_array($record->status, [BookingStatus::CANCELLED, BookingStatus::REJECTED, BookingStatus::COMPLETED]))
            ->form([
                DatePicker::make('new_date')
                    ->label('Tanggal Baru')
                    ->required()
                    ->minDate(now()->toDateString())
                    ->default(fn (Reservasi $record) => $record->booking_date?->format('Y-m-d')),
                TextInput::make('new_time')
                    ->label('Jam Baru')
                    ->placeholder('09:00')
                    ->default(fn (Reservasi $record) => $record->booking_time ?: '09:00'),
                TextInput::make('reason')
                    ->label('Catatan Alasan Reschedule')
                    ->placeholder('Permintaan pelanggan / penyesuaian teknisi'),
                Toggle::make('override_quota')
                    ->label('Override Kuota Maksimal 5 Slot')
                    ->helperText('Aktifkan jika ada izin khusus mengabaikan kuota harian.'),
            ])
            ->action(function (Reservasi $record, array $data) {
                try {
                    app(BookingService::class)->rescheduleBooking(
                        $record,
                        $data['new_date'],
                        $data['new_time'] ?? null,
                        $data['reason'] ?? null,
                        (bool) ($data['override_quota'] ?? false)
                    );
                    Notification::make()->title('Jadwal booking berhasil diperbarui!')->success()->send();
                } catch (\Exception $e) {
                    Notification::make()->title('Gagal ubah jadwal')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public static function cancelBookingAction(): Action
    {
        return Action::make('cancelBooking')
            ->label('Batalkan')
            ->icon('heroicon-o-x-mark')
            ->color('danger')
            ->visible(fn (Reservasi $record): bool => !in_array($record->status, [BookingStatus::CANCELLED, BookingStatus::REJECTED, BookingStatus::COMPLETED]))
            ->requiresConfirmation()
            ->modalHeading('Batalkan Booking')
            ->modalDescription('Slot kuota pada tanggal ini akan otomatis kembali tersedia untuk pelanggan lain.')
            ->form([
                Textarea::make('alasan')
                    ->label('Alasan Pembatalan')
                    ->required()
                    ->placeholder('Masukkan alasan pembatalan booking...'),
            ])
            ->action(function (Reservasi $record, array $data) {
                try {
                    app(BookingService::class)->cancelBooking($record, $data['alasan']);
                    Notification::make()->title('Booking berhasil dibatalkan dan kuota slot dikembalikan!')->success()->send();
                } catch (\Exception $e) {
                    Notification::make()->title('Gagal membatalkan')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public static function approveMaterialAction(): Action
    {
        return Action::make('approveMaterial')
            ->label('Setujui Bahan')
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->visible(fn (Reservasi $record): bool => $record->material_status === 'pending' && ($record->material_brand !== null || $record->layanan?->jenis_layanan === 'jasa_pasang'))
            ->requiresConfirmation()
            ->modalHeading('Verifikasi Bahan Customer')
            ->modalDescription('Apakah material stiker/vinyl yang dibawa customer sudah diperiksa dan layak pasang?')
            ->action(function (Reservasi $record) {
                $record->update(['material_status' => 'approved']);
                Notification::make()->title('Material Customer Dinyatakan Layak Pasang!')->success()->send();
            });
    }

    public static function rejectMaterialAction(): Action
    {
        return Action::make('rejectMaterial')
            ->label('Tolak Bahan')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->visible(fn (Reservasi $record): bool => $record->material_status === 'pending' && ($record->material_brand !== null || $record->layanan?->jenis_layanan === 'jasa_pasang'))
            ->form([
                Textarea::make('admin_notes')
                    ->label('Alasan Penolakan Bahan')
                    ->required()
                    ->placeholder('Contoh: Panjang bahan kurang 2 meter / lem rusak / berkerut parah.'),
            ])
            ->action(function (Reservasi $record, array $data) {
                $record->update([
                    'material_status' => 'rejected',
                    'admin_notes'     => $data['admin_notes'],
                ]);
                Notification::make()->title('Material Customer Ditolak')->danger()->send();
            });
    }

    public static function sendQuotationAction(): Action
    {
        return Action::make('sendQuotation')
            ->label('Beri Quotation')
            ->icon('heroicon-o-banknotes')
            ->color('primary')
            ->visible(fn (Reservasi $record): bool => $record->quotation_status === 'pending' || ($record->layanan?->jenis_layanan === 'custom' && empty($record->quotation_amount)))
            ->form([
                TextInput::make('quotation_amount')
                    ->label('Nominal Penawaran Resmi (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Textarea::make('quotation_notes')
                    ->label('Rincian Biaya & Catatan Teknis')
                    ->required()
                    ->placeholder('Rincian bahan khusus, durasi kerja, dan spesifikasi pemasangan.'),
            ])
            ->action(function (Reservasi $record, array $data) {
                $amount = (float) $data['quotation_amount'];
                $dp = round($amount * 0.3);
                $record->update([
                    'quotation_amount'           => $amount,
                    'quotation_notes'            => $data['quotation_notes'],
                    'quotation_status'           => 'sent',
                    'base_price_snapshot'        => $amount,
                    'grand_total_snapshot'       => $amount,
                    'dp_amount_snapshot'         => $dp,
                    'remaining_balance_snapshot' => $amount - $dp,
                ]);
                Notification::make()->title('Quotation Resmi Berhasil Disimpan!')->success()->send();
            });
    }

    public static function whatsappAction(): Action
    {
        return Action::make('whatsapp')
            ->label('WhatsApp')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('success')
            ->url(fn (Reservasi $record) => $record->whatsapp_notification_url)
            ->openUrlInNewTab();
    }

    public static function viewDetailAction(): Action
    {
        return Action::make('view')
            ->label('Detail')
            ->icon('heroicon-o-eye')
            ->url(fn (Reservasi $record): string => BookingResource::getUrl('view', ['record' => $record]));
    }
}
