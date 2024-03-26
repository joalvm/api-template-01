<?php

namespace App\DataObjects\Repositories\Users;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class LoginSessionData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        #[MapName('remember_me')]
        public bool $rememberMe = false,
        public string $host = '',
        public string $ip = '',
        public string $browser = '',
        #[MapName('browser_version')]
        public string $browserVersion = '',
        public string $platform = '',
        #[MapName('platform_version')]
        public string $platformVersion = '',
    ) {
    }
}
