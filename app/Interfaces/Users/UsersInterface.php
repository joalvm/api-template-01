<?php

namespace App\Interfaces\Users;

use App\Enums\UserRole;
use App\Interfaces\BaseInterface;
use App\Models\User\User;
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
     */
    public function save(array $data): User;

    /**
     * Actualiza un recurso user.
     *
     * @param int $id
     */
    public function update($id, array $data): User;

    /**
     * Elimina un recurso user.
     *
     * @param int $id
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo del repsoitorio.
     *
     * @param int $id
     */
    public function getModel($id): User;

    /**
     * Obtiene el modelo del repositorio usando un id de persona.
     */
    public function getModelByPerson(int $personId): User;

    /**
     * Obtiene el modelo del repositorio usando la columna email.
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
     * Establece el filtro por clientes.
     *
     * @param int[]|null $clients
     */
    public function setClients($clients): static;

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
