<?php

namespace App\DataObjects\Repositories\Users;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class UpdateUserPasswordData extends Data
{
    public function __construct(
        #[MapName('current_password')]
        public string $currentPassword,
        public string $password,
        #[MapName('confirm_password')]
        public string $confirmPassword,
    ) {
    }
}
