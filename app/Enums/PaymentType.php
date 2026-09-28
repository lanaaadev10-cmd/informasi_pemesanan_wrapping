<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

enum PaymentType: string
{
    case DP = 'dp';
    case LUNAS = 'lunas';

    public function label(): string
    {
        return match ($this) {
            self::DP => 'Down Payment (DP)',
            self::LUNAS => 'Lunas (Cash)',
        };
    }

    public function validationRule(): Enum
    {
        return new Enum(self::class);
    }
}
