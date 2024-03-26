<?php

namespace App\Interfaces\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateProvinceData;
use App\DataObjects\Repositories\Ubigeo\UpdateProvinceData;
use App\Interfaces\BaseInterface;
use App\Models\Ubigeo\Province;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Exceptions\NotAcceptableException;
use Joalvm\Utils\Item;

interface ProvincesInterface extends BaseInterface
{
    /**
     * Lista todos los recursos province.
     */
    public function all(): Collection;

    /**
     * Busca un recurso province.
     *
     * @param int $id
     */
    public function find($id): ?Item;

    /**
     * Crea un recurso province.
     *
     * @throws ValidationException
     */
    public function save(CreateProvinceData $data): Province;

    /**
     * Actualiza un recurso province.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update($id, UpdateProvinceData $data): Province;

    /**
     * Elimina un recurso province.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     * @throws NotAcceptableException
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo province.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel($id): Province;

    /**
     * Establece el filtro por codigo ubigeo.
     *
     * @param string[]|null $codes
     */
    public function setCodes($codes): static;

    /**
     * Establece el filtro por id de departamentos.
     *
     * @param int[]|null $departments
     */
    public function setDepartments($departments): static;

    /**
     * Establece el filtro por codigos de departmentos.
     *
     * @param string[]|null $departmentCodes
     */
    public function setDepartmentCodes($departmentCodes): static;
}
