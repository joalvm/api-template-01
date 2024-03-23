<?php

namespace App\Interfaces\Ubigeo;

use App\Interfaces\BaseInterface;
use App\Models\Ubigeo\Province;
use Joalvm\Utils\Collection;
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
     */
    public function save(array $data): Province;

    /**
     * Actualiza un recurso province.
     *
     * @param int $id
     */
    public function update($id, array $data): Province;

    /**
     * Elimina un recurso province.
     *
     * @param int $id
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo province.
     *
     * @param int $id
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
