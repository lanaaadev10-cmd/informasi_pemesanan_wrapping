<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum as EnumRule;

/**
 * Payment Method Enum
 */
enum PaymentMethod: string
{
    case TRANSFER_BANK = 'transfer_bank';
    case TRANSFER_E_WALLET = 'transfer_e_wallet';
    case CASH = 'cash';

    public function label(): string
    {
        return match($this) {
            self::TRANSFER_BANK => 'Transfer Bank',
            self::TRANSFER_E_WALLET => 'Transfer E-Wallet',
            self::CASH => 'Tunai',
        };
    }

    public static function validationRule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
