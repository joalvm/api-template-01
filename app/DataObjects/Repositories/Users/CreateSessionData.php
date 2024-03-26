<?php

namespace App\DataObjects\Repositories\Users;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class CreateSessionData extends Data
{
    public function __construct(
        #[MapName('user_id')]
        public int $userId,
        public string $token,
        #[WithCast(DateTimeInterfaceCast::class, format: \DateTimeInterface::ATOM)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: \DateTimeInterface::ATOM)]
        #[MapName('expire_at')]
        public \DateTimeImmutable|Optional|null $expireAt,
        public string $ip,
        public string $browser,
        #[MapName('browser_version')]
        public string $browserVersion,
        public string $platform,
        #[MapName('platform_version')]
        public string $platformVersion,
    ) {
    }
}
