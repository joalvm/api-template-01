<?php

namespace App\Repositories\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateDistrictData;
use App\DataObjects\Repositories\Ubigeo\UpdateDistrictData;
use App\Interfaces\Ubigeo\DistrictsInterface;
use App\Models\Ubigeo\District;
use App\Repositories\Repository;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

class DistrictsRepository extends Repository implements DistrictsInterface
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

    /**
     * @var int[]
     */
    protected array $provinces = [];

    /**
     * @var string[]
     */
    protected array $provinceCodes = [];

    public function __construct(public District $model)
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

    public function save(CreateDistrictData $data): District
    {
        $model = $this->model->newInstance($data->all());

        $model->validate()->save();

        return $model;
    }

    public function update($id, UpdateDistrictData $data): District
    {
        $model = $this->getModel($id);

        $model->fill($data->all());

        $model->validate()->update();

        return $model;
    }

    public function delete($id): bool
    {
        return $this->getModel($id)->delete();
    }

    public function getModel($id): District
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function setCodes($codes): static
    {
        $this->codes = to_list($codes);

        return $this;
    }

    public function setProvinces($provinces): static
    {
        $this->provinces = to_list_int($provinces);

        return $this;
    }

    public function setProvinceCodes($provinceCodes): static
    {
        $this->provinceCodes = to_list($provinceCodes);

        return $this;
    }

    public function setDepartments($departments): static
    {
        $this->departments = to_list_int($departments);

        return $this;
    }

    public function setDepartmentCodes($departmentCodes): static
    {
        $this->departmentCodes = to_list($departmentCodes);

        return $this;
    }

    private function builder(): Builder
    {
        return $this->filters(
            Builder::table('public.districts', 'ds')
                ->schema($this->schema())
                ->join('public.provinces as p', 'p.id', 'ds.province_id')
                ->join('public.departments as d', 'd.id', 'p.department_id')
                ->casts(function (Item $item) {
                    $item->floatValues(['latitude', 'longitude']);
                })
        )->whereNull(['ds.deleted_at', 'p.deleted_at', 'd.deleted_at']);
    }

    private function filters(Builder $builder): Builder
    {
        if ($this->codes) {
            $builder->whereIn('ds.code', $this->codes);
        }

        if ($this->departments) {
            $builder->whereIn('d.id', $this->departments);
        }

        if ($this->departmentCodes) {
            $builder->whereIn('d.code', $this->departmentCodes);
        }

        if ($this->provinces) {
            $builder->whereIn('p.id', $this->provinces);
        }

        if ($this->provinceCodes) {
            $builder->whereIn('p.code', $this->provinceCodes);
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
            'province:p' => [
                'id',
                'name',
                'code',
            ],
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
