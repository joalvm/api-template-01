<?php

namespace App\Repositories;

use App\DataObjects\Repositories\CreateDocumentTypeData;
use App\DataObjects\Repositories\UpdateDocumentTypeData;
use App\Enums\CharType;
use App\Enums\LengthType;
use App\Exceptions\CannotDeleteDocumentTypeWithPersonsException;
use App\Interfaces\DocumentTypesInterface;
use App\Models\DocumentType;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

class DocumentTypesRepository extends Repository implements DocumentTypesInterface
{
    protected ?CharType $charType = null;

    /**
     * @var LengthType[]
     */
    protected array $lengthTypes = [];

    public function __construct(public DocumentType $model)
    {
    }

    public function all(): Collection
    {
        return $this->builder()->all();
    }

    public function find($id): ?Item
    {
        return $this->builder()->find($id);
    }

    public function create(CreateDocumentTypeData $data): DocumentType
    {
        $model = $this->model->newInstance($data->all());

        $model->validate()->save();

        return $model;
    }

    public function update(mixed $id, UpdateDocumentTypeData $data): DocumentType
    {
        $model = $this->getModel($id);

        $model->fill($data->all());

        $model->validate()->update();

        return $model;
    }

    public function delete($id): bool
    {
        $model = $this->getModel($id);

        if (($count = $model->persons()->count()) > 0) {
            throw new CannotDeleteDocumentTypeWithPersonsException($count);
        }

        return $model->delete();
    }

    public function getModel($id): DocumentType
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function setCharType($charType): static
    {
        if (!is_string($charType)) {
            return $this;
        }

        if (CharType::has($charType)) {
            $this->charType = CharType::from($charType);
        }

        return $this;
    }

    public function setLengthTypes($lengthTypes): static
    {
        $this->lengthTypes = array_filter(
            to_list($lengthTypes),
            function ($lengthType) {
                return LengthType::has($lengthType);
            }
        );

        return $this;
    }

    public function builder(): Builder
    {
        return $this->filters(
            Builder::table('public.document_types', 'dt')
                ->schema($this->schema())
        )->whereNull('dt.deleted_at');
    }

    public function filters(Builder $builder): Builder
    {
        if ($this->charType) {
            $builder->where('dt.char_type', $this->charType->value);
        }

        if ($this->lengthTypes) {
            $builder->whereIn('dt.length_type', $this->lengthTypes);
        }

        return $builder;
    }

    public function schema(): array
    {
        return [
            'id',
            'name',
            'abbr',
            'length_type',
            'length',
            'char_type',
            'created_at',
            'updated_at',
        ];
    }
}
