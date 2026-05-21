<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum as EnumRule;

/**
 * Notification Type Enum
 */
enum NotificationType: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case IN_APP = 'in_app';
    case PUSH = 'push';

    public function label(): string
    {
        return match($this) {
            self::EMAIL => 'Email',
            self::SMS => 'SMS',
            self::IN_APP => 'Notifikasi In-App',
            self::PUSH => 'Push Notification',
        };
    }

    public static function validationRule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
