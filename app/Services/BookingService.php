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
use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingPayment;
use App\Models\Layanan;
use App\Models\User;
use App\Models\VehicleModel;
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

        $basePrice = (float) $layanan->harga;

        // Proses Add-ons jika ada
        $addons = collect();
        $addonsTotal = 0.0;
        if (!empty($data['addons']) && is_array($data['addons'])) {
            $addons = Addon::whereIn('id', $data['addons'])->where('is_active', true)->get();
            $addonsTotal = (float) $addons->sum('price');
        }

        $grandTotal = $basePrice + $addonsTotal;

        $paymentType = $data['payment_type'] ?? 'dp';
        if ($paymentType === PaymentType::DP->value) {
            $dpAmount = $this->calculateDpAmount($layanan) + round($addonsTotal * 0.5);
            $amount = $dpAmount;
        } else {
            $dpAmount = round($grandTotal * 0.5);
            $amount = $grandTotal;
        }
        $remainingBalance = max(0, $grandTotal - $dpAmount);

        // Resolusi Brand, Model & Ukuran Kendaraan
        $brandId = $data['brand_id'] ?? null;
        $modelId = $data['model_id'] ?? null;
        $vehicleSize = $data['vehicle_size'] ?? null;
        $vehicleName = $data['vehicle_name'] ?? null;

        if ($modelId) {
            $vModel = VehicleModel::with('brand')->find($modelId);
            if ($vModel) {
                $brandId = $vModel->brand_id;
                $vehicleSize = $vehicleSize ?: $vModel->vehicle_size;
                $vehicleName = $vehicleName ?: ($vModel->brand->name . ' ' . $vModel->name);
            }
        }
        $vehicleName = $vehicleName ?: 'Mobil Pelanggan';

        // Upload Foto Bahan Pelanggan (Khusus Jasa Pasang)
        $materialPhotoPath = null;
        if (isset($data['material_photo']) && $data['material_photo'] instanceof UploadedFile) {
            $materialPhotoPath = $data['material_photo']->store('material_photos', 'public');
        }

        // Upload Foto Referensi (Khusus Custom Project)
        $customPhotoPath = null;
        if (isset($data['custom_reference_photo']) && $data['custom_reference_photo'] instanceof UploadedFile) {
            $customPhotoPath = $data['custom_reference_photo']->store('custom_photos', 'public');
        }

        $proofPath = $proofFile ? $proofFile->store('bukti_booking', 'public') : null;

        $processBooking = function () use (
            $user, $data, $layanan, $bookingDate, $paymentType, $amount, $proofPath,
            $basePrice, $addonsTotal, $grandTotal, $dpAmount, $remainingBalance,
            $brandId, $modelId, $vehicleSize, $vehicleName,
            $materialPhotoPath, $customPhotoPath, $addons
        ) {
            DB::beginTransaction();
            try {
                $this->slotKuotaService->assertSlotAvailable($bookingDate);

                if ($user && !$user->hasRole('admin')) {
                    $this->assertSlotAvailableForUser($user, $bookingDate);
                }

                $booking = Booking::create([
                    'booking_code'               => 'BKG-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'user_id'                    => $user?->id,
                    'customer_name'              => $data['customer_name'] ?? ($user?->name ?? 'Pelanggan'),
                    'customer_phone'             => $data['customer_phone'] ?? ($user?->no_hp ?? ($user?->phone ?? '-')),
                    'customer_email'             => $data['customer_email'] ?? $user?->email,
                    'layanan_id'                 => $layanan->id_layanan,
                    'booking_date'               => $bookingDate,
                    'booking_time'               => $data['booking_time'] ?? null,
                    'payment_type'               => $paymentType,
                    'brand_id'                   => $brandId,
                    'model_id'                   => $modelId,
                    'vehicle_size'               => $vehicleSize,
                    'vehicle_name'               => $vehicleName,
                    'vehicle_color'              => $data['vehicle_color'] ?? null,
                    'vehicle_license'            => $data['vehicle_license'] ?? null,
                    'source'                     => $data['source'] ?? 'online',
                    'service_name_snapshot'      => $layanan->nama_layanan,
                    'base_price_snapshot'        => $basePrice,
                    'addons_total_snapshot'      => $addonsTotal,
                    'grand_total_snapshot'       => $grandTotal,
                    'dp_amount_snapshot'         => $dpAmount,
                    'remaining_balance_snapshot' => $remainingBalance,
                    'material_brand'             => $data['material_brand'] ?? null,
                    'material_size'              => $data['material_size'] ?? null,
                    'material_condition'         => $data['material_condition'] ?? null,
                    'material_photo'             => $materialPhotoPath,
                    'material_disclaimer_agreed' => !empty($data['material_disclaimer_agreed']),
                    'material_status'            => ($layanan->jenis_layanan === 'jasa_pasang') ? 'pending' : 'approved',
                    'custom_request_notes'       => $data['custom_request_notes'] ?? null,
                    'custom_reference_photo'     => $customPhotoPath,
                    'quotation_status'           => ($layanan->jenis_layanan === 'custom' || $layanan->tipe_layanan === 'custom') ? 'pending' : 'none',
                    'notes'                      => $data['notes'] ?? null,
                    'status'                     => BookingStatus::PENDING->value,
                ]);

                // Simpan item addons terpilih
                foreach ($addons as $addon) {
                    BookingAddon::create([
                        'booking_id'     => $booking->id,
                        'addon_id'       => $addon->id,
                        'addon_name'     => $addon->name,
                        'price_snapshot' => $addon->price,
                    ]);
                }

                BookingPayment::create([
                    'booking_id'     => $booking->id,
                    'payment_method' => $data['payment_method'] ?? 'transfer_bank',
                    'amount'         => $amount,
                    'proof_file'     => $proofPath,
                    'status'         => 'pending',
                ]);

                DB::commit();

                $this->slotKuotaService->clearCache($bookingDate);
                $booking->load(['layanan', 'user', 'payment', 'addons']);
                event(new BookingCreated($booking));

                return $booking;
            } catch (\Throwable $e) {
                DB::rollBack();
                if ($proofPath) {
                    Storage::disk('public')->delete($proofPath);
                }
                if ($materialPhotoPath) {
                    Storage::disk('public')->delete($materialPhotoPath);
                }
                if ($customPhotoPath) {
                    Storage::disk('public')->delete($customPhotoPath);
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
     * Admin membuat booking manual via Panel Kasir (Offline / Walk-In).
     */
    public function createManualBooking(array $data, bool $overrideQuota = false): Booking
    {
        $bookingDate = $data['booking_date'];

        if (!$overrideQuota) {
            $this->slotKuotaService->assertSlotAvailable($bookingDate);
        }

        $layanan = !empty($data['layanan_id']) ? Layanan::find($data['layanan_id']) : null;
        $basePrice = (float) ($layanan?->harga ?? 0);

        $quotationAmount = !empty($data['quotation_amount']) ? (float) $data['quotation_amount'] : null;
        if ($quotationAmount !== null && $quotationAmount > 0) {
            $basePrice = $quotationAmount;
        }

        $grandTotal = $basePrice;

        $amount = $basePrice;
        $dpAmount = round($basePrice * 0.5);
        if (($data['payment_type'] ?? 'lunas') === PaymentType::DP->value) {
            if ($quotationAmount !== null && $quotationAmount > 0) {
                $dpAmount = round($quotationAmount * 0.5);
            } elseif ($layanan) {
                $dpAmount = $this->calculateDpAmount($layanan);
            }
            $amount = $dpAmount;
        }
        $remainingBalance = max(0, $grandTotal - $dpAmount);

        DB::beginTransaction();
        try {
            $isCustomLayanan = ($data['tipe_pemesanan'] ?? null) === 'custom'
                || empty($data['layanan_id'])
                || ($layanan?->jenis_layanan === 'custom' || $layanan?->tipe_layanan === 'custom')
                || ($quotationAmount !== null && $quotationAmount > 0);

            $serviceName = !empty($data['custom_project_name'])
                ? trim($data['custom_project_name'])
                : ($layanan?->nama_layanan ?? ($quotationAmount ? 'Proyek Custom Wrapping (Deal WhatsApp)' : 'Layanan Manual'));

            $booking = Booking::create([
                'booking_code'               => 'BKG-M-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id'                    => $data['user_id'] ?? null,
                'customer_name'              => $data['customer_name'] ?? ($data['user_name'] ?? 'Pelanggan Manual'),
                'customer_phone'             => $data['customer_phone'] ?? '-',
                'customer_email'             => $data['customer_email'] ?? null,
                'layanan_id'                 => $data['layanan_id'] ?? null,
                'booking_date'               => $bookingDate,
                'booking_time'               => $data['booking_time'] ?? null,
                'payment_type'               => $data['payment_type'] ?? 'lunas',
                'vehicle_name'               => $data['vehicle_name'] ?? 'Mobil Pelanggan',
                'vehicle_color'              => $data['vehicle_color'] ?? null,
                'vehicle_license'            => $data['vehicle_license'] ?? null,
                'brand_id'                   => $data['brand_id'] ?? null,
                'model_id'                   => $data['model_id'] ?? null,
                'vehicle_size'               => $data['vehicle_size'] ?? null,
                'source'                     => 'offline',
                'service_name_snapshot'      => $serviceName,
                'base_price_snapshot'        => $basePrice,
                'addons_total_snapshot'      => 0,
                'grand_total_snapshot'       => $grandTotal,
                'dp_amount_snapshot'         => $dpAmount,
                'remaining_balance_snapshot' => $remainingBalance,
                'quotation_amount'           => $quotationAmount,
                'quotation_notes'            => $data['quotation_notes'] ?? null,
                'quotation_status'           => $isCustomLayanan ? ($data['quotation_status'] ?? 'sent') : 'none',
                'custom_request_notes'       => $data['custom_request_notes'] ?? null,
                'notes'                      => $data['notes'] ?? null,
                'status'                     => $data['status'] ?? BookingStatus::CONFIRMED->value,
                'admin_notes'                => $data['admin_notes'] ?? ($overrideQuota ? '[Admin Override Kuota]' : null),
            ]);

            BookingPayment::create([
                'booking_id'     => $booking->id,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'amount'         => $amount,
                'proof_file'     => null,
                'status'         => in_array($booking->status, [BookingStatus::CONFIRMED, BookingStatus::APPROVED, BookingStatus::COMPLETED]) ? 'verified' : 'pending',
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