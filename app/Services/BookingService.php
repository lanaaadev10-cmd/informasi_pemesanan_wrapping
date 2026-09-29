<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use App\Events\BookingCompleted;
use App\Events\BookingConfirmed;
use App\Events\BookingCreated;
use App\Events\BookingPaymentUploaded;
use App\Events\BookingPaymentVerified;
use App\Events\BookingRejected;
use App\Exceptions\Booking\BookingDuplicateException;
use App\Exceptions\Booking\BookingEmailUnverifiedException;
use App\Exceptions\SlotPenuhException;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service untuk manage booking operations.
 *
 * Menangani: kuota harian, pembuatan booking (anti-overbooking via
 * pessimistic locking), upload bukti bayar, verifikasi admin, dan
 * siklus status booking.
 */
class BookingService
{
    /**
     * @deprecated Gunakan SlotKuotaService::MAX_SLOT_PER_DAY (5) yang berlaku bersama
     *             untuk Booking dan Pesanan. Konstanta ini dipertahankan untuk backward compat.
     */
    public const MAX_BOOKINGS_PER_DAY = SlotKuotaService::MAX_SLOT_PER_DAY;

    /** Status yang menghabiskan kuota harian (aktif). */
    public const ACTIVE_STATUSES = SlotKuotaService::BOOKING_ACTIVE_STATUSES;

    public function __construct(
        protected NotifikasiService $notifikasiService,
        protected SlotKuotaService $slotKuotaService,
    ) {}

    /**
     * Cek kuota suatu tanggal.
     * Didelegasikan ke SlotKuotaService agar kuota dihitung bersama
     * antara Booking dan Pesanan (shared 5 slot/hari).
     *
     * @return array{available: int, is_full: bool, booked_count: int, pesanan_count: int, total_used: int, max: int}
     */
    public function checkQuota(string $date): array
    {
        return $this->slotKuotaService->checkQuota($date);
    }

    /**
     * Status kuota hari ini (untuk widget landing page).
     */
    public function getTodayQuota(): array
    {
        return $this->slotKuotaService->getTodayQuota();
    }

    /**
     * Kuota per tanggal dalam satu bulan untuk kalender landing page.
     *
     * @return array<string, array>
     */
    public function getMonthQuota(int $year, int $month): array
    {
        return $this->slotKuotaService->getMonthQuota($year, $month);
    }

    /**
     * Ambil daftar layanan aktif untuk form booking.
     */
    public function getAvailableLayanans()
    {
        return Layanan::query()
            ->whereIn('tipe_layanan', ['fix', 'custom'])
            ->orderBy('nama_layanan')
            ->get();
    }

    /**
     * Hitung jumlah DP yang harus dibayar (50% dari harga paket).
     */
    public function calculateDpAmount(Layanan $layanan): float
    {
        return round(((float) $layanan->harga) * 0.5);
    }

    /**
     * Buat booking baru + catat pembayaran dengan anti-overbooking.
     *
     * File bukti transfer disimpan terlebih dahulu (filesystem tanpa
     * transaksi), lalu dibuat booking didalam DB::transaction.
     */
    public function createBooking(?User $user, array $data, ?UploadedFile $proofFile = null): Booking
    {
        $layanan = Layanan::findOrFail($data['layanan_id']);
        $bookingDate = $data['booking_date'];

        $paymentType = $data['payment_type'] ?? 'dp';
        $amount = $paymentType === PaymentType::DP->value
            ? $this->calculateDpAmount($layanan)
            : (float) $layanan->harga;

        $proofPath = null;
        if ($proofFile) {
            $proofPath = $proofFile->store('bukti_booking', 'public');
        }

        $processBooking = function () use (
            $user,
            $data,
            $layanan,
            $bookingDate,
            $paymentType,
            $amount,
            $proofPath
        ) {
            DB::beginTransaction();
            try {
                // Cek kuota gabungan (booking + pesanan) dengan pessimistic lock.
                // Dilempar SlotPenuhException jika sudah mencapai 5 slot/hari atau tanggal diblokir.
                $this->slotKuotaService->assertSlotAvailable($bookingDate);

                // Mencegah double-click submit instan yang sama dari user non-admin
                if ($user && !$user->hasRole('admin')) {
                    $this->assertSlotAvailableForUser($user, $bookingDate);
                }

                $customerName = $data['customer_name'] ?? ($user?->name ?? 'Pelanggan');
                $customerPhone = $data['customer_phone'] ?? ($user?->no_hp ?? ($user?->phone ?? '-'));
                $customerEmail = $data['customer_email'] ?? $user?->email;

                $booking = Booking::create([
                    'booking_code' => 'BKG-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'user_id' => $user?->id,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'customer_email' => $customerEmail,
                    'layanan_id' => $layanan->id_layanan,
                    'booking_date' => $bookingDate,
                    'booking_time' => $data['booking_time'] ?? null,
                    'payment_type' => $paymentType,
                    'vehicle_name' => $data['vehicle_name'] ?? 'Mobil Pelanggan',
                    'vehicle_color' => $data['vehicle_color'] ?? null,
                    'vehicle_license' => $data['vehicle_license'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'status' => BookingStatus::PENDING->value,
                ]);

                BookingPayment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => $data['payment_method'] ?? 'transfer_bank',
                    'amount' => $amount,
                    'proof_file' => $proofPath,
                    'status' => 'pending',
                ]);

                DB::commit();

                // Hapus cache kuota setelah booking berhasil dibuat
                $this->slotKuotaService->clearCache($bookingDate);

                $booking->load(['layanan', 'user', 'payment']);
                event(new BookingCreated($booking));

                return $booking;
            } catch (\Throwable $e) {
                DB::rollBack();

                // Bersihkan file jika transaksi gagal (filesystem tanpa rollback).
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
            // Driver tidak mendukung lock (misal: array driver pada phpunit test)
        } catch (\Throwable $e) {
            if (
                $e instanceof SlotPenuhException ||
                $e instanceof BookingDuplicateException ||
                $e instanceof BookingEmailUnverifiedException ||
                $e instanceof \Illuminate\Validation\ValidationException
            ) {
                throw $e;
            }
            \Illuminate\Support\Facades\Log::warning("Cache lock unavailable: {$e->getMessage()}. Executing DB pessimistic lock fallback.");
        }

        return $processBooking();
    }

    /**
     * Admin membuat booking manual.
     * Mengikuti batas kuota 5/hari kecuali $overrideQuota = true.
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
                'booking_code' => 'BKG-M-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id' => $data['user_id'] ?? null,
                'customer_name' => $data['customer_name'] ?? 'Pelanggan Manual',
                'customer_phone' => $data['customer_phone'] ?? '-',
                'customer_email' => $data['customer_email'] ?? null,
                'layanan_id' => $data['layanan_id'],
                'booking_date' => $bookingDate,
                'booking_time' => $data['booking_time'] ?? null,
                'payment_type' => $data['payment_type'] ?? 'lunas',
                'vehicle_name' => $data['vehicle_name'] ?? 'Mobil Pelanggan',
                'vehicle_color' => $data['vehicle_color'] ?? null,
                'vehicle_license' => $data['vehicle_license'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'] ?? BookingStatus::CONFIRMED->value,
                'admin_notes' => $data['admin_notes'] ?? ($overrideQuota ? '[Admin Override Kuota]' : null),
            ]);

            BookingPayment::create([
                'booking_id' => $booking->id,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'amount' => $amount,
                'proof_file' => null,
                'status' => in_array($booking->status, [BookingStatus::CONFIRMED, BookingStatus::APPROVED, BookingStatus::COMPLETED]) ? 'approved' : 'pending',
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

    /**
     * Admin mengubah jadwal booking (Reschedule).
     */
    public function rescheduleBooking(Booking $booking, string $newDate, ?string $newTime = null, ?string $adminNotes = null, bool $overrideQuota = false): Booking
    {
        $oldDate = $booking->booking_date ? $booking->booking_date->toDateString() : null;

        if ($oldDate !== $newDate && !$overrideQuota) {
            $this->slotKuotaService->assertSlotAvailable($newDate);
        }

        DB::beginTransaction();
        try {
            $notes = $booking->admin_notes ? $booking->admin_notes . "\n" : '';
            $notes .= "[Reschedule " . now()->format('d/m/Y H:i') . "] Dari {$oldDate} ke {$newDate}";
            if ($adminNotes) {
                $notes .= ": {$adminNotes}";
            }

            $booking->update([
                'booking_date' => $newDate,
                'booking_time' => $newTime ?: $booking->booking_time,
                'admin_notes' => $notes,
            ]);

            DB::commit();

            if ($oldDate) {
                $this->slotKuotaService->clearCache($oldDate);
            }
            $this->slotKuotaService->clearCache($newDate);

            return $booking->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Admin mengonfirmasi booking (PENDING -> CONFIRMED -> AWAITING_PAYMENT).
     *
     * Jika bukti pembayaran sudah diunggah saat pembuatan booking,
     * status otomatis berlanjut ke PAYMENT_UPLOADED agar admin
     * bisa langsung memverifikasi pembayaran.
     */
    public function confirmBooking(Booking $booking, ?string $notes = null): Booking
    {
        $confirmed = $this->updateStatus($booking, BookingStatus::CONFIRMED, $notes);
        $awaiting = $this->updateStatus($confirmed, BookingStatus::AWAITING_PAYMENT, $notes);

        if ($awaiting->payment && $awaiting->payment->proof_file) {
            return $this->updateStatus($awaiting, BookingStatus::PAYMENT_UPLOADED, $notes);
        }

        return $awaiting;
    }

    /**
     * Admin menolak booking (PENDING/PAYMENT_UPLOADED -> REJECTED).
     */
    public function rejectBooking(Booking $booking, string $alasan): Booking
    {
        return $this->updateStatus($booking, BookingStatus::REJECTED, $alasan);
    }

    /**
     * User mengunggah bukti transfer.
     */
    public function uploadPaymentProof(Booking $booking, UploadedFile $file, string $paymentMethod = 'transfer_bank'): BookingPayment
    {
        if (!$booking->status->canUploadPayment()) {
            throw new \Exception("Booking tidak sedang menunggu pembayaran (status: {$booking->status->label()})");
        }

        // Pastikan record pembayaran ada (dibuat otomatis saat createBooking).
        $payment = $booking->payment;

        if (!$payment) {
            $payment = BookingPayment::create([
                'booking_id' => $booking->id,
                'payment_method' => $paymentMethod,
                'amount' => 0,
                'status' => 'pending',
            ]);
        }

        $path = $file->store('bukti_booking', 'public');

        DB::beginTransaction();
        try {
            $payment->update([
                'proof_file' => $path,
                'status' => 'pending',
            ]);

            $this->updateStatus($booking, BookingStatus::PAYMENT_UPLOADED);

            DB::commit();

            $payment->load('booking');
            event(new BookingPaymentUploaded($payment));

            return $payment;
        } catch (\Throwable $e) {
            DB::rollBack();
            Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    /**
     * Admin memverifikasi bukti bayar dengan pessimistic locking.
     */
    public function verifyPayment(BookingPayment $payment, ?string $notes = null): BookingPayment
    {
        DB::beginTransaction();
        try {
            $payment = BookingPayment::lockForUpdate()->findOrFail($payment->id);

            if (!$payment->isPending()) {
                throw new \Exception('Status pembayaran bukan pending');
            }

            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'admin_notes' => $notes,
            ]);

            $this->updateStatus($payment->booking, BookingStatus::APPROVED, $notes);

            DB::commit();

            $payment->load('booking.user', 'booking.layanan');
            event(new BookingPaymentVerified($payment->booking));

            return $payment->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Admin menolak bukti bayar: hapus file, balik ke AWAITING_PAYMENT.
     */
    public function rejectPayment(BookingPayment $payment, string $alasan): BookingPayment
    {
        if (!$payment->isPending()) {
            throw new \Exception('Status pembayaran bukan pending');
        }

        DB::beginTransaction();
        try {
            $this->deletePaymentProof($payment);

            $payment->update([
                'status' => 'rejected',
                'admin_notes' => $alasan,
            ]);

            $booking = $payment->booking;
            if ($booking->status === BookingStatus::PAYMENT_UPLOADED) {
                $this->updateStatus($booking, BookingStatus::AWAITING_PAYMENT, $alasan);
            }

            DB::commit();

            return $payment->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Admin mulai pengerjaan (APPROVED -> IN_PROGRESS).
     */
    public function startProcessing(Booking $booking, ?string $notes = null): Booking
    {
        return $this->updateStatus($booking, BookingStatus::IN_PROGRESS, $notes);
    }

    /**
     * Admin selesaikan pengerjaan (IN_PROGRESS -> COMPLETED).
     */
    public function completeBooking(Booking $booking): Booking
    {
        return $this->updateStatus($booking, BookingStatus::COMPLETED);
    }

    /**
     * User membatalkan booking (jika status mengizinkan).
     */
    public function cancelBooking(Booking $booking, ?string $alasan = null): Booking
    {
        if (!$booking->bisaDibatalkan()) {
            throw new \Exception(
                "Booking tidak dapat dibatalkan pada status {$booking->status->label()}"
            );
        }

        $result = $this->updateStatus(
            $booking,
            BookingStatus::CANCELLED,
            $alasan ?? 'Dibatalkan oleh customer'
        );

        // Refresh cache kuota karena slot sudah dibebaskan
        $this->slotKuotaService->clearCache($booking->booking_date->toDateString());

        return $result;
    }

    /**
     * Update status dengan validasi state machine (konsisten dengan PesananService).
     */
    public function updateStatus(Booking $booking, BookingStatus $newStatus, ?string $notes = null): Booking
    {
        $currentStatus = $booking->status;

        if (!in_array($newStatus, $currentStatus->validTransitions(), true)) {
            throw new \Exception(
                "Transisi tidak diizinkan dari {$currentStatus->label()} ke {$newStatus->label()}"
            );
        }

        DB::beginTransaction();
        try {
            // Jangan timpa admin_notes dengan null pada transisi biasa
            // (mis. completeBooking) agar catatan admin tidak hilang.
            $attributes = ['status' => $newStatus->value];
            if ($notes !== null) {
                $attributes['admin_notes'] = $notes;
            }
            $booking->update($attributes);

            DB::commit();

            // Event dikirim setelah commit agar listener tidak melihat data
            // setengah-transaksi dan punya catatan final yang konsisten.
            $this->emitStatusChangeEvent($booking, $newStatus);

            return $booking->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Guard bisnis: satu user hanya boleh punya 1 booking aktif per tanggal
     * (mencegah double-submit / memborong slot). Null-kan excludeBookingId
     * saat dipakai untuk membuat baru.
     */
    public function assertSlotAvailableForUser(User $user, string $bookingDate, ?int $excludeBookingId = null): void
    {
        $alreadyBooked = Booking::query()
            ->where('user_id', $user->id)
            ->whereDate('booking_date', $bookingDate)
            ->whereIn('status', [
                'pending',
                'confirmed',
                'awaiting_payment',
                'payment_uploaded',
                'approved',
                'in_progress',
            ])
            ->when($excludeBookingId, fn ($query) => $query->where('id', '!=', $excludeBookingId))
            ->exists();

        if ($alreadyBooked) {
            throw new BookingDuplicateException($bookingDate);
        }
    }

    /**
     * Detail booking dengan semua relasi.
     */
    public function getBookingDetails(int $id): Booking
    {
        return Booking::with([
            'user',
            'layanan',
            'payment',
        ])->findOrFail($id);
    }

    /**
     * Daftar booking milik user dengan pagination.
     */
    public function getUserBookings(int $userId, int $perPage = 10, ?string $status = null, ?string $tab = null): LengthAwarePaginator
    {
        $query = Booking::with(['layanan', 'payment'])
            ->where('user_id', $userId);

        $filter = $tab ?: $status;

        if ($filter && $filter !== 'all') {
            match ($filter) {
                'unpaid', 'menunggu_bayar', 'awaiting_payment' => $query->where('status', BookingStatus::AWAITING_PAYMENT->value),
                'processing', 'diproses', 'active', 'berjalan' => $query->whereIn('status', [
                    BookingStatus::PENDING->value,
                    BookingStatus::CONFIRMED->value,
                    BookingStatus::PAYMENT_UPLOADED->value,
                    BookingStatus::APPROVED->value,
                    BookingStatus::IN_PROGRESS->value,
                ]),
                'completed', 'selesai' => $query->where('status', BookingStatus::COMPLETED->value),
                'cancelled', 'dibatalkan', 'rejected', 'ditolak', 'batal' => $query->whereIn('status', [
                    BookingStatus::CANCELLED->value,
                    BookingStatus::REJECTED->value,
                ]),
                default => $query->where('status', $filter),
            };
        }

        return $query->orderByDesc('booking_date')->paginate($perPage)->withQueryString();
    }

    /**
     * Statistik ringkas booking milik user (untuk header "Booking Saya").
     * Satu query agregat, bukan N query per status.
     */
    public function getUserBookingStats(int $userId): array
    {
        $counts = Booking::query()
            ->where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $sum = fn (array $statuses): int => collect($statuses)->sum(
            fn (string $status): int => (int) ($counts[$status] ?? 0)
        );

        $unpaidCount = (int) ($counts[BookingStatus::AWAITING_PAYMENT->value] ?? 0);
        $processingCount = $sum([
            BookingStatus::PENDING->value,
            BookingStatus::CONFIRMED->value,
            BookingStatus::PAYMENT_UPLOADED->value,
            BookingStatus::APPROVED->value,
            BookingStatus::IN_PROGRESS->value,
        ]);
        $completedCount = (int) ($counts[BookingStatus::COMPLETED->value] ?? 0);
        $cancelledCount = $sum([
            BookingStatus::CANCELLED->value,
            BookingStatus::REJECTED->value,
        ]);

        return [
            'total' => $counts->sum(),
            'pending' => (int) ($counts[BookingStatus::PENDING->value] ?? 0),
            'menunggu_bayar' => $sum([
                BookingStatus::AWAITING_PAYMENT->value,
                BookingStatus::PAYMENT_UPLOADED->value,
            ]),
            'aktif' => $processingCount,
            'selesai' => $completedCount,

            // Tab agregat bersih (Opsi Rekomendasi 1)
            'tab_all' => $counts->sum(),
            'tab_unpaid' => $unpaidCount,
            'tab_processing' => $processingCount,
            'tab_completed' => $completedCount,
            'tab_cancelled' => $cancelledCount,
        ];
    }

    /**
     * Daftar semua booking (filament + admin) dengan filter.
     */
    public function getAllBookings(int $perPage = 15, ?string $status = null, ?string $date = null): LengthAwarePaginator
    {
        $query = Booking::with(['user', 'layanan', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->where('booking_date', $date);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    /**
     * Hapus file bukti bayar.
     */
    public function deletePaymentProof(BookingPayment $payment): void
    {
        if ($payment->proof_file) {
            Storage::disk('public')->delete($payment->proof_file);
        }
        $payment->update(['proof_file' => null]);
    }

    /**
     * Emit event sesuai status baru untuk memicu listener.
     */
    protected function emitStatusChangeEvent(Booking $booking, BookingStatus $status): void
    {
        $bookingWithRelations = $booking->load([
            'user',
            'layanan',
            'payment',
        ]);

        match ($status) {
            BookingStatus::CONFIRMED => event(new BookingConfirmed($bookingWithRelations)),
            BookingStatus::APPROVED => null, // ticket untuk PaymentVerified
            BookingStatus::COMPLETED => event(new BookingCompleted($bookingWithRelations)),
            BookingStatus::REJECTED => event(new BookingRejected($bookingWithRelations)),
            BookingStatus::CANCELLED => event(new BookingRejected($bookingWithRelations, alasan: 'Dibatalkan')),
            default => null,
        };
    }
}