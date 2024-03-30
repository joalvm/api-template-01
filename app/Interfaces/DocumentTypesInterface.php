<?php

namespace App\Interfaces;

use App\DataObjects\Repositories\CreateDocumentTypeData;
use App\DataObjects\Repositories\UpdateDocumentTypeData;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Exceptions\ForbiddenException;
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
    public function find($id): ?Item;

    /**
     * Crea un recurso document_type.
     *
     * @throws ValidationException
     * @throws ForbiddenException
     */
    public function save(CreateDocumentTypeData $data): DocumentType;

    /**
     * Actualiza un recurso document_type.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update($id, UpdateDocumentTypeData $data): DocumentType;

    /**
     * Elimina un recurso document_type.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     * @throws DeletingInUseDocumentTypeException
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo document_type.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
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
