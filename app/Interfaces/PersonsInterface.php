<?php

namespace App\Interfaces;

use App\Models\Person;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

interface PersonsInterface extends BaseInterface
{
    /**
     * Obtiene todos los recursos persons.
     */
    public function all(): Collection;

    /**
     * Obtiene un recurso person.
     *
     * @param int $id
     */
    public function find($id): ?Item;

    /**
     * Crea un nuevo recurso person.
     *
     * @throws ValidationException
     */
    public function save(array $data): Person;

    /**
     * Actualiza un recurso person.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update($id, array $data): Person;

    /**
     * Elimina un recurso person.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function delete($id): bool;

    /**
     * Obtiene el modelo person.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel($id): Person;

    /**
     * Establece el filtro por tipo de documentos.
     *
     * @param int[] $documentTypes
     */
    public function setDocumentTypes($documentTypes): static;

    /**
     * Establece el filtro por el genero de las personas.
     *
     * @param string $gender
     */
    public function setGender($gender): static;

    /**
     * Establece el filtro por codigo de documento de identidad.
     *
     * @param string[]|null $idDocuments
     */
    public function setIdDocuments($idDocuments): static;
}
