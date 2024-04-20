<?php

namespace App\Interfaces\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateDistrictData;
use App\DataObjects\Repositories\Ubigeo\UpdateDistrictData;
use App\Interfaces\BaseInterface;
use App\Models\Ubigeo\District;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

interface DistrictsInterface extends BaseInterface
{
    /**
     * Lista todos los recursos district.
     */
    public function all(): Collection;

    /**
     * Busca un recurso district.
     *
     * @param int $id
     */
    public function find(mixed $id): ?Item;

    /**
     * Crea un recurso district.
     *
     * @throws ValidationException
     */
    public function create(CreateDistrictData $data): District;

    /**
     * Actualiza un recurso district.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update(mixed $id, UpdateDistrictData $data): District;

    /**
     * Elimina un recurso district.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function delete(mixed $id): bool;

    /**
     * Obtiene el modelo del recurso district.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel(mixed $id): District;

    /**
     * Establece el filtro por codigo ubigeo.
     *
     * @param string[]|null $codes
     */
    public function setCodes(mixed $codes): static;

    /**
     * Establece el filtro por id de provincias.
     *
     * @param int[]|null $provinces
     */
    public function setProvinces(mixed $provinces): static;

    /**
     * Establece el filtro por codigo ubigeo de las provincias.
     *
     * @param string[]|null $provinceCodes
     */
    public function setProvinceCodes(mixed $provinceCodes): static;

    /**
     * Establece el filtro por id de departamentos.
     *
     * @param int[]|null $departments
     */
    public function setDepartments(mixed $departments): static;

    /**
     * Establece el filtro por codigos de departmentos.
     *
     * @param string[]|null $departmentCodes
     */
    public function setDepartmentCodes(mixed $departmentCodes): static;
}
