<?php

namespace App\Interfaces\Users;

use App\DataObjects\Repositories\Users\CreateSessionData;
use App\DataObjects\Repositories\Users\LoginSessionData;
use App\Exceptions\Auth\SessionDisabledException;
use App\Exceptions\Auth\SessionNotFoundException;
use App\Exceptions\Auth\WrongAuthException;
use App\Interfaces\BaseInterface;
use App\Models\User\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Item;

interface SessionsInterface extends BaseInterface
{
    /**
     * Crea un nuevo recurso session.
     *
     * @throws ValidationException
     */
    public function create(CreateSessionData $data): Session;

    /**
     * Obtiene el profile del usuario.
     */
    public function profile(int $userId): ?Item;

    /**
     * Crea una session para un usuario con autorización.
     *
     * @throws WrongAuthException
     * @throws SessionDisabledException
     */
    public function login(LoginSessionData $data, bool $validatePassword = true): Item;

    /**
     * Cierra la sessión activa.
     *
     * @throws ModelNotFoundException
     */
    public function logout(int $sessionId): bool;

    /**
     * Obtiene y valida la información de una session.
     *
     * @throws SessionNotFoundException
     */
    public function authenticate(int $sessionId): array;
}
