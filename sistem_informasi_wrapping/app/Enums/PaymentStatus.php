<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum as EnumRule;

/**
 * Payment Status Enum
 * 
 * Lifecycle: pending → verified / rejected
 */
enum PaymentStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Verifikasi',
            self::VERIFIED => 'Terverifikasi',
            self::REJECTED => 'Ditolak',
            self::EXPIRED => 'Kadaluarsa',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::PENDING => 'orange',
            self::VERIFIED => 'green',
            self::REJECTED => 'red',
            self::EXPIRED => 'gray',
        };
    }

    public static function validationRule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
