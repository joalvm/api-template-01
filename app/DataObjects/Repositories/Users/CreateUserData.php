<?php

namespace App\DataObjects\Repositories\Users;

use App\Enums\UserRole;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CreateUserData extends Data
{
    public function __construct(
        #[MapName('person_id')]
        public int $personId,
        public string $email,
        public string|Optional $password,
        public UserRole $role = UserRole::USER,
        #[MapName('avatar_url')]
        public ?string $avatarUrl = null,
        #[MapName('super_admin')]
        public bool|Optional $superAdmin = false,
    ) {
    }
}
