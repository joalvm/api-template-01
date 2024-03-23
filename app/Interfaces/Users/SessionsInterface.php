<?php

namespace App\Interfaces\Users;

use App\Interfaces\BaseInterface;
use App\Models\User\Session;
use Joalvm\Utils\Item;

interface SessionsInterface extends BaseInterface
{
    /**
     * Crea un nuevo recurso session.
     */
    public function save(array $data): Session;

    /**
     * Obtiene el profile del usuario.
     */
    public function profile(): Item;

    /**
     * Crea una session para un usuario con autorización.
     */
    public function login(array $data, bool $validatePassword = true): Item;

    /**
     * Cierra la sessión activa.
     */
    public function logout(int $sessionId): bool;

    /**
     * Obtiene y valida la información de una session.
     */
    public function authenticate(int $sessionId): array;
}
