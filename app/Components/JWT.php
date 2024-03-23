<?php

namespace App\Components;

use Firebase\JWT\JWT as BaseJWT;
use Firebase\JWT\Key;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class JWT
{
    public const HS512 = 'HS512';
    public const HS256 = 'HS256';

    public static function decode(string $token): \stdClass
    {
        return BaseJWT::decode(
            $token,
            new Key(Config::get('app.key'), self::HS512)
        );
    }

    public static function decodeHS256(string $token): \stdClass
    {
        return BaseJWT::decode(
            $token,
            Config::get('app.key'),
            [self::HS256]
        );
    }

    /**
     * Crea un JWT token pudiendo personalizar la fecha de expiración,
     * el valor retornado es un array[string $token, Carbon $expire].
     */
    public static function encode(
        array $payload,
        int|\DateTimeInterface $expireMinutes = 4320
    ) {
        $now = Carbon::now();
        $expire = is_int($expireMinutes)
            ? Carbon::now()->addMinutes($expireMinutes)
            : Carbon::instance($expireMinutes);

        $token = BaseJWT::encode(
            array_merge($payload, [
                'iat' => $now->unix(),
                'exp' => $expire->unix(),
            ]),
            Config::get('app.key'),
            self::HS512
        );

        return [$token, $expire];
    }
}
