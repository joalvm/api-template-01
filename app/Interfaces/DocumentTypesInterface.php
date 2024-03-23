<?php

namespace App\Interfaces;

use App\Models\DocumentType;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

interface DocumentTypesInterface extends BaseInterface
{
    /**
     * Lista todos los recursos document_type.
     */
    public function all(): Collection;

    /**
     * Busca un recurso document_type.
     *
     * @param int $id
     */
    public function find($id): Item;

    /**
     * Crea un recurso document_type.
     */
    public function save(array $data): DocumentType;

    /**
     * Actualiza un recurso document_type.
     *
     * @param int $id
     */
    public function update($id, array $data): DocumentType;

    /**
     * Elimina un recurso document_type.
     *
     * @param int $id
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo document_type.
     *
     * @param int $id
     */
    public function getModel($id): DocumentType;

    /**
     * Establece el filtro por tipo de caracteres.
     *
     * @param string|null $charType
     */
    public function setCharType($charType): static;

    /**
     * Establece el filtro por tipos de tamaños.
     *
     * @param string|null $lengthTypes
     */
    public function setLengthTypes($lengthTypes): static;
}
