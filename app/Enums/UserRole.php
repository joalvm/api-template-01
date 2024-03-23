<?php

namespace App\Enums;

enum UserRole: string
{
    use Enum;

    // Usuario del cliente.
    case ADMIN = 'ADMIN';

    // Usuario regular.
    case USER = 'USER';

    public static function isUser(string $type): bool
    {
        return self::USER->value === $type;
    }

    public static function isAdmin(string $type): bool
    {
        return self::ADMIN->value === $type;
    }
}
