<?php

namespace App\Interfaces\Ubigeo;

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
    public function find($id): ?Item;

    /**
     * Crea un recurso district.
     *
     * @throws ValidationException
     */
    public function save(array $data): District;

    /**
     * Actualiza un recurso district.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update($id, array $data): District;

    /**
     * Elimina un recurso district.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo del recurso district.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel($id): District;

    /**
     * Establece el filtro por codigo ubigeo.
     *
     * @param string|null $codes
     */
    public function setCodes($codes): static;

    /**
     * Establece el filtro por la union de todos los codigos ubigeo.
     *
     * @param string[]|null $ubigeoCodes
     */
    public function setUbigeoCodes($ubigeoCodes): static;

    /**
     * Establece el filtro por id de provincias.
     *
     * @param int[]|null $provinces
     */
    public function setProvinces($provinces): static;

    /**
     * Establece el filtro por codigo ubigeo de las provincias.
     *
     * @param string[]|null $provinceCodes
     */
    public function setProvinceCodes($provinceCodes): static;

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
