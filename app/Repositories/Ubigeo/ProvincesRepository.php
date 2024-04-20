<?php

namespace App\Repositories\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateProvinceData;
use App\DataObjects\Repositories\Ubigeo\UpdateProvinceData;
use App\Exceptions\Ubigeo\CannotDeleteProvinceWithDistrictsException;
use App\Interfaces\Ubigeo\ProvincesInterface;
use App\Models\Ubigeo\Province;
use App\Repositories\Repository;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

class ProvincesRepository extends Repository implements ProvincesInterface
{
    /**
     * @var string[]
     */
    protected array $codes = [];

    /**
     * @var int[]
     */
    protected array $departments = [];

    /**
     * @var string[]
     */
    protected array $departmentCodes = [];

    public function __construct(public Province $model)
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

    public function create(CreateProvinceData $data): Province
    {
        $model = $this->model->newInstance($data->all());

        $model->validate()->save();

        return $model;
    }

    public function update(mixed $id, UpdateProvinceData $data): Province
    {
        $model = $this->getModel($id);

        $model->fill($data->all());

        $model->validate()->update();

        return $model;
    }

    public function delete(mixed $id): bool
    {
        $model = $this->getModel($id);

        if (($count = $model->districts()->count()) > 0) {
            throw new CannotDeleteProvinceWithDistrictsException($count);
        }

        return $model->delete();
    }

    public function getModel(mixed $id): Province
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function setCodes(mixed $codes): static
    {
        $this->codes = to_list($codes);

        return $this;
    }

    public function setDepartments(mixed $departments): static
    {
        $this->departments = to_list_int($departments);

        return $this;
    }

    public function setDepartmentCodes(mixed $departmentCodes): static
    {
        $this->departmentCodes = to_list($departmentCodes);

        return $this;
    }

    private function builder(): Builder
    {
        return $this->filters(
            Builder::table('public.provinces', 'p')
                ->schema($this->schema())
                ->join('public.departments as d', 'd.id', 'p.department_id')
                ->casts(function (Item $item) {
                    $item->floatValues(['latitude', 'longitude']);
                })
        )->whereNull(['p.deleted_at', 'd.deleted_at']);
    }

    private function filters(Builder $builder): Builder
    {
        if ($this->codes) {
            $builder->whereIn('p.code', $this->codes);
        }

        if ($this->departments) {
            $builder->whereIn('d.id', $this->departments);
        }

        if ($this->departmentCodes) {
            $builder->whereIn('d.code', $this->departmentCodes);
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
            'department:d' => [
                'id',
                'name',
                'code',
            ],
            'created_at',
            'updated_at',
        ];
    }
}
