<?php

namespace App\Enums;

use Joalvm\Utils\Traits\ExtendsEnums;

enum UserRole: string
{
    use ExtendsEnums;

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
