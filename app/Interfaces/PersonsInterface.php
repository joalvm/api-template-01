<?php

namespace App\Interfaces;

use App\DataObjects\Repositories\CreatePersonData;
use App\DataObjects\Repositories\UpdatePersonData;
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
    public function find(mixed $id): ?Item;

    /**
     * Crea un nuevo recurso person.
     *
     * @throws ValidationException
     */
    public function create(CreatePersonData $data): Person;

    /**
     * Actualiza un recurso person.
     *
     * @param int $id
     *
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function update(mixed $id, UpdatePersonData $data): Person;

    /**
     * Elimina un recurso person.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function delete(mixed $id): bool;

    /**
     * Obtiene el modelo person.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function getModel(mixed $id): Person;

    /**
     * Establece el filtro por tipo de documentos.
     *
     * @param int[] $documentTypes
     */
    public function setDocumentTypes(mixed $documentTypes): static;

    /**
     * Establece el filtro por el genero de las personas.
     *
     * @param string $gender
     */
    public function setGender(mixed $gender): static;

    /**
     * Establece el filtro por codigo de documento de identidad.
     *
     * @param string[]|null $idDocuments
     */
    public function setIdDocuments(mixed $idDocuments): static;
}
