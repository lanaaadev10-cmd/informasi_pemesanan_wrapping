<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case AWAITING_PAYMENT = 'awaiting_payment';
    case PAYMENT_UPLOADED = 'payment_uploaded';
    case APPROVED = 'approved';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Konfirmasi',
            self::CONFIRMED => 'Dikonfirmasi',
            self::AWAITING_PAYMENT => 'Menunggu Pembayaran',
            self::PAYMENT_UPLOADED => 'Bukti Terkirim',
            self::APPROVED => 'Disetujui',
            self::IN_PROGRESS => 'Sedang Dikerjakan',
            self::COMPLETED => 'Selesai',
            self::REJECTED => 'Ditolak',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'info',
            self::AWAITING_PAYMENT => 'gray',
            self::PAYMENT_UPLOADED => 'warning',
            self::APPROVED => 'success',
            self::IN_PROGRESS => 'primary',
            self::COMPLETED => 'success',
            self::REJECTED => 'danger',
            self::CANCELLED => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::CONFIRMED => 'heroicon-o-check-badge',
            self::AWAITING_PAYMENT => 'heroicon-o-credit-card',
            self::PAYMENT_UPLOADED => 'heroicon-o-magnifying-glass',
            self::APPROVED => 'heroicon-o-check-circle',
            self::IN_PROGRESS => 'heroicon-o-wrench-screwdriver',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
            self::CANCELLED => 'heroicon-o-x-mark',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::CONFIRMED,
            self::AWAITING_PAYMENT,
            self::PAYMENT_UPLOADED,
            self::APPROVED,
        ]);
    }

    public function canUploadPayment(): bool
    {
        return $this === self::AWAITING_PAYMENT;
    }

    public function validTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::CONFIRMED, self::REJECTED, self::CANCELLED],
            self::CONFIRMED => [self::AWAITING_PAYMENT, self::REJECTED, self::CANCELLED],
            self::AWAITING_PAYMENT => [self::PAYMENT_UPLOADED, self::CANCELLED],
            self::PAYMENT_UPLOADED => [self::APPROVED, self::REJECTED, self::CANCELLED],
            self::APPROVED => [self::IN_PROGRESS, self::REJECTED, self::CANCELLED],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED => [],
            self::REJECTED => [],
            self::CANCELLED => [],
        };
    }

    public function validationRule(): Enum
    {
        return new Enum(self::class);
    }
}
