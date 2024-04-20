<?php

namespace App\Interfaces\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateDepartmentData;
use App\DataObjects\Repositories\Ubigeo\UpdateDepartmentData;
use App\Exceptions\Ubigeo\CannotDeleteDepartmentWithProvincesException;
use App\Interfaces\BaseInterface;
use App\Models\Ubigeo\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

interface DepartmentsInterface extends BaseInterface
{
    /**
     * Lista todos los recursos department.
     */
    public function all(): Collection;

    /**
     * Busca un recurso department.
     *
     * @param int $id
     */
    public function find(mixed $id): ?Item;

    /**
     * Crea un recurso department.
     *
     * @throws ValidationException
     */
    public function create(CreateDepartmentData $data): Department;

    /**
     * Actualiza un recurso department.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update(mixed $id, UpdateDepartmentData $data): Department;

    /**
     * Elimina un recurso department.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     * @throws CannotDeleteDepartmentWithProvincesException
     */
    public function delete(mixed $id): bool;

    /**
     * Obtiene el modelo del recurso department.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel(mixed $id): Department;

    /**
     * Establece el filtro por codigo ubigeo.
     *
     * @param string[]|null $codes
     */
    public function setCodes(mixed $codes): static;
}
