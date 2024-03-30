<?php

namespace Tests\Traits;

use App\Rules\Pgsql\IntegerRule;

trait Providers
{
    public static $unknownId = 99999;

    /**
     * Datos para validar un campo string requerido.
     */
    public static function StringRequiredProvider(): array
    {
        return [
            ...self::requiredProvider(),
            ...self::stringProvider(),
        ];
    }

    /**
     * Datos para validar un campo requerido de tipo int4 positivo.
     */
    public static function integerPositiveRequiredProvider(): array
    {
        return [
            ...self::requiredProvider(),
            ...self::integerProvider(),
            ...self::integerPositiveProvider(),
            ...self::pgIntegerMaxProvider(),
        ];
    }

    /**
     * Datos para validar un campo requerido de tipo int2 positivo.
     */
    public static function smallIntegerPositiveRequiredProvider(): array
    {
        return [
            ...self::requiredProvider(),
            ...self::integerProvider(),
            ...self::integerPositiveProvider(),
            ...self::pgSmallIntegerMaxProvider(),
        ];
    }

    /**
     * Datos para validar un campo requerido de tipo string solo con letras, espacios.
     */
    public static function alphaSpaceRequiredProvider(): array
    {
        return [
            ...self::requiredProvider(),
            ...self::stringProvider(),
            ...self::stringWithSymbolsProvider(),
            ...self::stringWithNumbersProvider(),
        ];
    }

    /**
     * Datos para validar un campo requerido de tipo string y con valores especificos.
     */
    public static function enumRequiredProvider(): array
    {
        return [
            ...self::requiredProvider(),
            ...self::stringProvider(),
            ...self::enumMissingProvider(),
        ];
    }

    /**
     * Datos para validar un campo requerido de tipo id(entero positivo).
     * Se incluye el caso de un id desconocido.
     */
    public static function integerIdRequiredProvider(): array
    {
        return [
            ...self::integerPositiveRequiredProvider(),
            ...self::unknownIdProvider(),
        ];
    }

    /**
     * Datos para validar un campo requerido de tipo email.
     */
    public static function EmailRequiredProvider(): array
    {
        return [
            ...self::requiredProvider(),
            ...self::stringProvider(),
            'is invalid email' => ['invalid email'],
        ];
    }

    public static function requiredProvider(): array
    {
        return [
            'is null' => [null],
            'is empty' => [''],
        ];
    }

    public static function stringProvider(): array
    {
        return [
            'is boolean' => [true],
            'is integer' => [1],
            'is array' => [['a' => 'b']],
        ];
    }

    public static function integerProvider(): array
    {
        return [
            'is boolean' => [true],
            'is string' => ['string'],
            'is float' => [1.1],
            'is array' => [['a' => 'b']],
        ];
    }

    public static function floatProvider(): array
    {
        return [
            'is boolean' => [true],
            'is string' => ['string'],
            'is array' => [['a' => 'b']],
        ];
    }

    public static function booleanProvider(): array
    {
        return [
            'is string' => ['string'],
            'is integer' => [1],
            'is array' => [['a' => 'b']],
        ];
    }

    public static function arrayProvider(): array
    {
        return [
            'is boolean' => [true],
            'is string' => ['string'],
            'is integer' => [1],
        ];
    }

    public static function integerPositiveProvider(): array
    {
        return [
            'is negative' => [-1],
            'is zero' => [0],
        ];
    }

    public static function floatPositiveProvider(): array
    {
        return [
            'is negative' => [-1.1],
            'is zero' => [0],
        ];
    }

    public static function stringWithSymbolsProvider(): array
    {
        return [
            'is string with symbol' => ['ab@c'],
        ];
    }

    public static function stringWithNumbersProvider(): array
    {
        return [
            'is string with numbers' => ['abc123'],
        ];
    }

    public static function pgSmallIntegerMinProvider(): array
    {
        return [
            'is less than min' => [IntegerRule::MIN_SMALL_INT - 1],
        ];
    }

    public static function pgSmallIntegerMaxProvider(): array
    {
        return [
            'is greater than max' => [IntegerRule::MAX_SMALL_INT + 1],
        ];
    }

    public static function pgIntegerMinProvider(): array
    {
        return [
            'is less than min' => [IntegerRule::MIN_INT - 1],
        ];
    }

    public static function pgIntegerMaxProvider(): array
    {
        return [
            'is greater than max' => [IntegerRule::MAX_INT + 1],
        ];
    }

    public static function pgBigIntegerMinProvider(): array
    {
        return [
            'is less than min' => [IntegerRule::MIN_BIG_INT - 1],
        ];
    }

    public static function pgBigIntegerMaxProvider(): array
    {
        return [
            'is greater than max' => [IntegerRule::MAX_BIG_INT + 1],
        ];
    }

    public static function enumMissingProvider(): array
    {
        return [
            'is unknown value' => ['unknown value'],
        ];
    }

    public static function unknownIdProvider(): array
    {
        return [
            'is unknown id' => [self::$unknownId],
        ];
    }
}
