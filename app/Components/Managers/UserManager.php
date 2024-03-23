<?php

namespace App\Components\Managers;

use App\Enums\UserRole;
use App\Interfaces\Users\UsersInterface;
use Illuminate\Support\Arr;
use Joalvm\Utils\Exceptions\NotAcceptableException;
use Joalvm\Utils\Item;

class UserManager
{
    protected array $user = [];

    protected array $person = [];

    public function load(array $user)
    {
        $this->user = Arr::except($user, ['person']);
        $this->person = Arr::get($user, 'person', []);
    }

    public function loadFromUserId(int $userId): void
    {
        /** @var UsersInterface $repository */
        $repository = app()->make(UsersInterface::class);

        $data = $repository->getAuthData($userId);

        if (empty($data)) {
            throw new NotAcceptableException('No se encontró el usuario');
        }

        $this->load($data);
    }

    public function id(): ?int
    {
        return Arr::get($this->user, 'id');
    }

    public function personId(): ?int
    {
        return Arr::get($this->person, 'id');
    }

    public function role(): ?UserRole
    {
        if (empty($this->user)) {
            return null;
        }

        return UserRole::tryFrom(Arr::get($this->user, 'role'));
    }

    public function enabled(): bool
    {
        return Arr::get($this->user, 'enabled', false);
    }

    public function person(): ?Item
    {
        if (empty($this->person)) {
            return null;
        }

        return new Item($this->person);
    }

    public function documentType(): ?Item
    {
        if (!$this->person) {
            return null;
        }

        return new Item(Arr::get($this->person, 'document_type'));
    }

    public function isSuperAdmin(): bool
    {
        if (!$this->user) {
            return false;
        }

        return Arr::get($this->user, 'super_admin');
    }

    public function isAdmin(): bool
    {
        if (!$this->user) {
            return false;
        }

        return UserRole::isAdmin(Arr::get($this->user, 'role'));
    }

    public function isUser(): bool
    {
        if (!$this->user) {
            return false;
        }

        return UserRole::isUser(Arr::get($this->user, 'role'));
    }
}
