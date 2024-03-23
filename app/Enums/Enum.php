<?php

namespace App\Enums;

trait Enum
{
    public static function has(?string $value): bool
    {
        if (is_null($value)) {
            return false;
        }

        return !is_null(self::tryFrom($value));
    }

    /**
     * Obtiene la instancia de un enum, si no existe devuelve null.
     */
    public static function get(mixed $value): ?static
    {
        return static::tryFrom(to_str($value));
    }

    /**
     * Obtiene un valor aleatoriamente.
     */
    public static function random(): static
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }
}
