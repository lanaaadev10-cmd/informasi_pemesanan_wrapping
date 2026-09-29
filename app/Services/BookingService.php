<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use App\Events\BookingCompleted;
use App\Events\BookingConfirmed;
use App\Events\BookingCreated;
use App\Events\BookingRejected;
use App\Exceptions\Booking\BookingDuplicateException;
use App\Exceptions\Booking\BookingEmailUnverifiedException;
use App\Exceptions\SlotPenuhException;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Layanan;
use App\Models\User;
use App\Services\Traits\ManagesBookingLifecycle;
use App\Services\Traits\ManagesBookingPayments;
use App\Services\Traits\ManagesBookingQueries;
use App\Services\Traits\ManagesBookingSlots;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Service untuk mengelola siklus operasional booking.
 * - Logika kuota & slot didelegasikan ke ManagesBookingSlots trait.
 * - Logika pembayaran & bukti bayar didelegasikan ke ManagesBookingPayments trait.
 * - Logika query & statistik didelegasikan ke ManagesBookingQueries trait.
 * - Logika status & lifecycle didelegasikan ke ManagesBookingLifecycle trait.
 */
class BookingService
{
    use ManagesBookingSlots;
    use ManagesBookingPayments;
    use ManagesBookingQueries;
    use ManagesBookingLifecycle;

    public const MAX_BOOKINGS_PER_DAY = SlotKuotaService::MAX_SLOT_PER_DAY;
    public const ACTIVE_STATUSES = SlotKuotaService::BOOKING_ACTIVE_STATUSES;

    public function __construct(
        protected NotifikasiService $notifikasiService,
        protected SlotKuotaService $slotKuotaService,
    ) {}

    /**
     * Buat booking baru + catat pembayaran dengan anti-overbooking.
     */
    public function createBooking(?User $user, array $data, ?UploadedFile $proofFile = null): Booking
    {
        $layanan = Layanan::findOrFail($data['layanan_id']);
        $bookingDate = $data['booking_date'];

        $paymentType = $data['payment_type'] ?? 'dp';
        $amount = $paymentType === PaymentType::DP->value
            ? $this->calculateDpAmount($layanan)
            : (float) $layanan->harga;

        $proofPath = $proofFile ? $proofFile->store('bukti_booking', 'public') : null;

        $processBooking = function () use ($user, $data, $layanan, $bookingDate, $paymentType, $amount, $proofPath) {
            DB::beginTransaction();
            try {
                $this->slotKuotaService->assertSlotAvailable($bookingDate);

                if ($user && !$user->hasRole('admin')) {
                    $this->assertSlotAvailableForUser($user, $bookingDate);
                }

                $booking = Booking::create([
                    'booking_code'    => 'BKG-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'user_id'         => $user?->id,
                    'customer_name'   => $data['customer_name'] ?? ($user?->name ?? 'Pelanggan'),
                    'customer_phone'  => $data['customer_phone'] ?? ($user?->no_hp ?? ($user?->phone ?? '-')),
                    'customer_email'  => $data['customer_email'] ?? $user?->email,
                    'layanan_id'      => $layanan->id_layanan,
                    'booking_date'    => $bookingDate,
                    'booking_time'    => $data['booking_time'] ?? null,
                    'payment_type'    => $paymentType,
                    'vehicle_name'    => $data['vehicle_name'] ?? 'Mobil Pelanggan',
                    'vehicle_color'   => $data['vehicle_color'] ?? null,
                    'vehicle_license' => $data['vehicle_license'] ?? null,
                    'notes'           => $data['notes'] ?? null,
                    'status'          => BookingStatus::PENDING->value,
                ]);

                BookingPayment::create([
                    'booking_id'     => $booking->id,
                    'payment_method' => $data['payment_method'] ?? 'transfer_bank',
                    'amount'         => $amount,
                    'proof_file'     => $proofPath,
                    'status'         => 'pending',
                ]);

                DB::commit();

                $this->slotKuotaService->clearCache($bookingDate);
                $booking->load(['layanan', 'user', 'payment']);
                event(new BookingCreated($booking));

                return $booking;
            } catch (\Throwable $e) {
                DB::rollBack();
                if ($proofPath) {
                    Storage::disk('public')->delete($proofPath);
                }
                throw $e;
            }
        };

        try {
            if (Cache::store()->supportsTags() || config('cache.default') !== 'array') {
                return Cache::lock("booking_slot_{$bookingDate}", 10)->block(5, $processBooking);
            }
        } catch (\BadMethodCallException $e) {
            // Driver tidak mendukung lock (misal: array driver pada test)
        } catch (\Throwable $e) {
            if ($e instanceof SlotPenuhException || $e instanceof BookingDuplicateException || $e instanceof BookingEmailUnverifiedException || $e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            \Illuminate\Support\Facades\Log::warning("Cache lock unavailable: {$e->getMessage()}. DB lock fallback.");
        }

        return $processBooking();
    }

    /**
     * Admin membuat booking manual (dengan opsi override kuota).
     */
    public function createManualBooking(array $data, bool $overrideQuota = false): Booking
    {
        $bookingDate = $data['booking_date'];

        if (!$overrideQuota) {
            $this->slotKuotaService->assertSlotAvailable($bookingDate);
        }

        $layanan = Layanan::find($data['layanan_id'] ?? null);
        $amount = (float) ($layanan?->harga ?? 0);
        if (($data['payment_type'] ?? 'lunas') === PaymentType::DP->value && $layanan) {
            $amount = $this->calculateDpAmount($layanan);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'booking_code'    => 'BKG-M-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id'         => $data['user_id'] ?? null,
                'customer_name'   => $data['customer_name'] ?? 'Pelanggan Manual',
                'customer_phone'  => $data['customer_phone'] ?? '-',
                'customer_email'  => $data['customer_email'] ?? null,
                'layanan_id'      => $data['layanan_id'],
                'booking_date'    => $bookingDate,
                'booking_time'    => $data['booking_time'] ?? null,
                'payment_type'    => $data['payment_type'] ?? 'lunas',
                'vehicle_name'    => $data['vehicle_name'] ?? 'Mobil Pelanggan',
                'vehicle_color'   => $data['vehicle_color'] ?? null,
                'vehicle_license' => $data['vehicle_license'] ?? null,
                'notes'           => $data['notes'] ?? null,
                'status'          => $data['status'] ?? BookingStatus::CONFIRMED->value,
                'admin_notes'     => $data['admin_notes'] ?? ($overrideQuota ? '[Admin Override Kuota]' : null),
            ]);

            BookingPayment::create([
                'booking_id'     => $booking->id,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'amount'         => $amount,
                'proof_file'     => null,
                'status'         => in_array($booking->status, [BookingStatus::CONFIRMED, BookingStatus::APPROVED, BookingStatus::COMPLETED]) ? 'approved' : 'pending',
            ]);

            DB::commit();
            $this->slotKuotaService->clearCache($bookingDate);
            $booking->load(['layanan', 'user', 'payment']);

            return $booking;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}