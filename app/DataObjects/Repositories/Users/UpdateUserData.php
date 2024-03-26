<?php

namespace App\DataObjects\Repositories\Users;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateUserData extends Data
{
    public function __construct(
        public string|Optional $avatarUrl,
        public bool|Optional $enabled,
    ) {
    }
}
