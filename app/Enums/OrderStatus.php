<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum as EnumRule;

/**
 * Order Status Enum
 * 
 * Defines all possible states in the order lifecycle.
 * Provides type safety and IDE autocompletion.
 * 
 * Lifecycle:
 * menunggu_konfirmasi_admin → menunggu_pembayaran → menunggu_verifikasi_pembayaran
 *   ↓
 * (rejected) → ditolak
 * (verified) → sedang_diproses → selesai
 */
enum OrderStatus: string
{
    case MENUNGGU_KONFIRMASI_ADMIN = 'menunggu_konfirmasi_admin';
    case MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    case MENUNGGU_VERIFIKASI_PEMBAYARAN = 'menunggu_verifikasi_pembayaran';
    case DIKONFIRMASI = 'dikonfirmasi';
    case SEDANG_DIPROSES = 'sedang_diproses';
    case SELESAI = 'selesai';
    case DITOLAK = 'ditolak';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::MENUNGGU_KONFIRMASI_ADMIN => 'Menunggu Konfirmasi Admin',
            self::MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::MENUNGGU_VERIFIKASI_PEMBAYARAN => 'Menunggu Verifikasi Pembayaran',
            self::DIKONFIRMASI => 'Dikonfirmasi',
            self::SEDANG_DIPROSES => 'Sedang Diproses',
            self::SELESAI => 'Selesai',
            self::DITOLAK => 'Ditolak',
        };
    }

    /**
     * Get badge color for UI
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::MENUNGGU_KONFIRMASI_ADMIN => 'yellow',
            self::MENUNGGU_PEMBAYARAN => 'blue',
            self::MENUNGGU_VERIFIKASI_PEMBAYARAN => 'orange',
            self::DIKONFIRMASI => 'green',
            self::SEDANG_DIPROSES => 'purple',
            self::SELESAI => 'green',
            self::DITOLAK => 'red',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this === self::MENUNGGU_KONFIRMASI_ADMIN
            || $this === self::MENUNGGU_PEMBAYARAN;
    }

    /**
     * Check if payment can be uploaded
     */
    public function canUploadPayment(): bool
    {
        return $this === self::MENUNGGU_PEMBAYARAN;
    }

    /**
     * Get valid transitions from current status
     */
    public function validTransitions(): array
    {
        return match($this) {
            self::MENUNGGU_KONFIRMASI_ADMIN => [
                self::MENUNGGU_PEMBAYARAN,
                self::DITOLAK,
            ],
            self::MENUNGGU_PEMBAYARAN => [
                self::MENUNGGU_VERIFIKASI_PEMBAYARAN,
                self::DITOLAK,
            ],
            self::MENUNGGU_VERIFIKASI_PEMBAYARAN => [
                self::DIKONFIRMASI,
                self::DITOLAK,
            ],
            self::DIKONFIRMASI => [
                self::SEDANG_DIPROSES,
            ],
            self::SEDANG_DIPROSES => [
                self::SELESAI,
            ],
            self::SELESAI => [],
            self::DITOLAK => [],
        };
    }

    /**
     * Validation rule for form requests
     */
    public static function validationRule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
