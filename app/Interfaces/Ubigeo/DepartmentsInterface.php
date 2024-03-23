<?php

namespace App\Interfaces\Ubigeo;

use App\Interfaces\BaseInterface;
use App\Models\Ubigeo\Department;
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
    public function find($id): ?Item;

    /**
     * Crea un recurso department.
     */
    public function save(array $data): Department;

    /**
     * Actualiza un recurso department.
     *
     * @param int $id
     */
    public function update($id, array $data): Department;

    /**
     * Elimina un recurso department.
     *
     * @param int $id
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo del recurso department.
     *
     * @param int $id
     */
    public function getModel($id): Department;

    /**
     * Establece el filtro por codigo ubigeo.
     *
     * @param string[]|null $codes
     */
    public function setCodes($codes): static;
}
