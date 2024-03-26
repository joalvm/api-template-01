<?php

namespace App\Interfaces\Users;

use App\DataObjects\Repositories\Users\CreateUserData;
use App\DataObjects\Repositories\Users\UpdateUserData;
use App\DataObjects\Repositories\Users\UpdateUserEmailData;
use App\DataObjects\Repositories\Users\UpdateUserPasswordData;
use App\Enums\UserRole;
use App\Exceptions\Users\AlreadyVerifiedUserException;
use App\Exceptions\Users\WrongCurrentPasswordException;
use App\Interfaces\BaseInterface;
use App\Models\User\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

interface UsersInterface extends BaseInterface
{
    /**
     * Obtiene toda la collection de users.
     */
    public function all(): Collection;

    /**
     * Encuentra un recurso user.
     *
     * @param int $id
     */
    public function find($id): ?Item;

    /**
     * Encuentra un recurso user mediante su username.
     */
    public function findByEmail(string $username): ?Item;

    /**
     * Crea un nuevo recurso user.
     *
     * @throws ValidationException
     * @throws AlreadyVerifiedUserException
     */
    public function save(CreateUserData $data): User;

    /**
     * Actualiza un recurso user.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     * @throws WrongCurrentPasswordException
     */
    public function update($id, UpdateUserData $data): User;

    /**
     * Actualiza la el correo electrónico de un usuario y su verificación del mismo.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     * @throws AlreadyVerifiedUserException
     */
    public function updateEmail($id, UpdateUserEmailData $data): User;

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param int $id
     *
     * @throws WrongCurrentPasswordException
     * @throws ModelNotFoundException
     */
    public function updatePassword($id, UpdateUserPasswordData $data): User;

    /**
     * Elimina un recurso user.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo del repsoitorio.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel($id): User;

    /**
     * Obtiene el modelo del repositorio usando un id de persona.
     *
     * @throws ModelNotFoundException
     */
    public function getModelByPerson(int $personId): User;

    /**
     * Obtiene el modelo del repositorio usando la columna email.
     *
     * @throws ModelNotFoundException
     */
    public function getModelByEmail(string $email): User;

    /**
     * Obtiene la información de autenticación de un usuario.
     */
    public function getAuthData(int $userId): array;

    /**
     * Establece el filtro por personas.
     *
     * @param int[]|null $persons
     */
    public function setPersons($persons): static;

    /**
     * Establece el filtro por tipos de usuarios.
     *
     * @param UserRole[]|null $roles
     */
    public function setRoles($roles): static;

    /**
     * Valida si la contraseña proporcionada es igual a la del modelo.
     */
    public function isValidPassword(User $model, string $password): bool;
}
