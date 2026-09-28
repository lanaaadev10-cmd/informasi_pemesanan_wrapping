<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Exceptions\Booking\BookingDuplicateException;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Pertahankan aturan bisnis saat tanggal/user booking diubah:
     * kuota harian tidak boleh lewat dan user tidak double-book di hari sama.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        $newDate = \Illuminate\Support\Carbon::parse($data['booking_date'])->toDateString();
        $oldDate = $record->booking_date?->toDateString();

        if ($newDate === $oldDate && (int) $data['user_id'] === (int) $record->user_id) {
            return $data;
        }

        $service = app(BookingService::class);

        // Kuota hari penuh? (tidak termasuk record yang sedang diedit)
        $bookedCount = Booking::query()
            ->where('booking_date', $newDate)
            ->whereIn('status', BookingService::ACTIVE_STATUSES)
            ->where('id', '!=', $record->id)
            ->count();

        if ($bookedCount >= BookingService::MAX_BOOKINGS_PER_DAY) {
            Notification::make()
                ->title('Kuota booking tanggal tersebut sudah penuh.')
                ->danger()
                ->send();
            $this->halt();
        }

        // User tidak boleh punya booking aktif lain di tanggal yang sama.
        try {
            $service->assertSlotAvailableForUser(
                User::findOrFail($data['user_id']),
                $newDate,
                excludeBookingId: $record->id,
            );
        } catch (BookingDuplicateException $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
            $this->halt();
        }

        return $data;
    }
}