<?php

namespace App\Repositories\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateDepartmentData;
use App\DataObjects\Repositories\Ubigeo\UpdateDepartmentData;
use App\Exceptions\Ubigeo\CannotDeleteDepartmentWithProvincesException;
use App\Interfaces\Ubigeo\DepartmentsInterface;
use App\Models\Ubigeo\Department;
use App\Repositories\Repository;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

class DepartmentsRepository extends Repository implements DepartmentsInterface
{
    /**
     * @var string[]
     */
    protected array $codes = [];

    public function __construct(public Department $model)
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

    public function create(CreateDepartmentData $data): Department
    {
        $model = $this->model->newInstance($data->all());

        $model->validate()->save();

        return $model;
    }

    public function update(mixed $id, UpdateDepartmentData $data): Department
    {
        $model = $this->getModel($id);

        $model->fill($data->all());

        $model->validate()->update();

        return $model;
    }

    public function delete(mixed $id): bool
    {
        $model = $this->getModel($id);

        if (($count = $model->provinces()->count()) > 0) {
            throw new CannotDeleteDepartmentWithProvincesException($count);
        }

        return $model->delete();
    }

    public function getModel(mixed $id): Department
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function setCodes(mixed $codes): static
    {
        $this->codes = to_list($codes);

        return $this;
    }

    private function builder(): Builder
    {
        return $this->filters(
            Builder::table('public.departments', 'd')
                ->schema($this->schema())
                ->casts(function (Item $item) {
                    $item->castFloatValues(['latitude', 'longitude']);
                })
        )->whereNull('deleted_at');
    }

    private function filters(Builder $builder): Builder
    {
        if ($this->codes) {
            $builder->whereIn('d.code', $this->codes);
        }

        return $builder;
    }

    private function schema(): array
    {
        return [
            'id',
            'name',
            'code',
            'latitude',
            'longitude',
            'created_at',
            'updated_at',
        ];
    }
}
