<?php

namespace App\Repositories;

use App\DataObjects\Repositories\CreatePersonData;
use App\DataObjects\Repositories\UpdatePersonData;
use App\Enums\Gender;
use App\Interfaces\PersonsInterface;
use App\Models\Person;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

class PersonsRepository extends Repository implements PersonsInterface
{
    /**
     * @var int[]
     */
    protected array $documentTypes = [];

    /**
     * @var string[]
     */
    protected array $idDocuments = [];

    protected ?Gender $gender = null;

    public function __construct(public Person $model)
    {
    }

    public function all(): Collection
    {
        return $this->builder()->all();
    }

    public function find(mixed $id): ?Item
    {
        return $this->builder()->find($id);
    }

    public function create(CreatePersonData $data): Person
    {
        $model = $this->model->newInstance($data->all());

        $model->validate()->save();

        return $model;
    }

    public function update(mixed $id, UpdatePersonData $data): Person
    {
        $model = $this->getModel($id)->fill($data->all());

        $model->validate()->update();

        return $model;
    }

    public function delete(mixed $id): bool
    {
        $model = $this->getModel($id);

        if ($result = $model->delete()) {
            $model->user()->delete();
        }

        return $result;
    }

    public function getModel(mixed $id): Person
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function setDocumentTypes($documentTypes): static
    {
        $this->documentTypes = to_list_int($documentTypes);

        return $this;
    }

    public function setGender(mixed $gender): static
    {
        if (Gender::has($gender)) {
            $this->gender = Gender::get($gender);
        }

        return $this;
    }

    public function setIdDocuments(mixed $idDocuments): static
    {
        $this->idDocuments = to_list($idDocuments);

        return $this;
    }

    private function builder(): Builder
    {
        return $this->filters(
            Builder::table('persons', 'p')
                ->schema($this->schema())
                ->join('public.document_types as dt', 'dt.id', 'p.document_type_id')
        )->whereNull('p.deleted_at');
    }

    private function filters(Builder $builder): Builder
    {
        if ($this->documentTypes) {
            $builder->whereIn('p.document_type_id', $this->documentTypes);
        }

        if ($this->gender) {
            $builder->where('p.gender', $this->gender->value);
        }

        if ($this->idDocuments) {
            $builder->whereIn('p.id_document', $this->idDocuments);
        }

        return $builder;
    }

    private function schema(): array
    {
        return [
            'id',
            'names',
            'last_names',
            'id_document',
            'email',
            'document_type:dt' => [
                'id',
                'name',
                'abbr',
                'length_type',
                'length',
                'char_type',
            ],
            'created_at',
            'updated_at',
        ];
    }
}
