<?php

namespace App\Components\Managers;

use App\Enums\UserRole;
use App\Facades\User;
use App\Interfaces\Users\SessionsInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Joalvm\Utils\Item;

class SessionManager
{
    private Item $session;

    private UserRole $userRole;

    private bool $isLogged = false;

    public function authenticate(int $sessionId): void
    {
        /** @var SessionsInterface $repository */
        $repository = App::make(SessionsInterface::class);

        $this->load($repository->authenticate($sessionId));
    }

    public function load(array $data): void
    {
        $this->session = new Item(Arr::get($data, 'session'));

        User::load(Arr::get($data, 'user'));

        $this->userRole = User::role();

        if ($this->session->get('id')) {
            $this->isLogged = true;
        }
    }

    public function id(): int
    {
        return $this->session->get('id');
    }

    /**
     * Mi rol puede ser uno de los roles que se pasan por parámetro.
     *
     * @param UserRole[] $roles
     */
    public function can(array $roles): bool
    {
        return in_array($this->userRole(), $roles);
    }

    public function userRole(): UserRole
    {
        return $this->userRole;
    }

    public function isLogged(): bool
    {
        return $this->isLogged;
    }

    public function isAdmin(): bool
    {
        return UserRole::isAdmin($this->userRole->value);
    }

    public function isSuperAdmin(): bool
    {
        return User::isSuperAdmin();
    }

    public function isUserBasic(): bool
    {
        return UserRole::isUser($this->userRole->value);
    }
}
