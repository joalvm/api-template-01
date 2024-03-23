<?php

namespace App\Models\Ubigeo;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property      int                             $id
 * @property      int                             $department_id
 * @property      string                          $name
 * @property      string                          $code
 * @property      float|null                      $latitude
 * @property      float|null                      $longitude
 * @property-read Department                      $department
 * @property-read Collection<array-key, District> $districts
 */
class Province extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'public.provinces';

    protected $fillable = [
        'department_id',
        'name',
        'code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'department_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function rules(): array
    {
        return [
            'department_id' => [
                'required',
                'integer',
                $this->ruleExistsDepartment(),
            ],
            'name' => [
                'required',
                'string',
                $this->ruleUniqueNamePerDepartment(),
            ],
            'code' => [
                'required',
                'string',
                'size:4',
                $this->ruleUniqueCode('code'),
            ],
            'latitude' => ['nullable', 'numeric', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'required_with:latitude'],
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }

    private function ruleExistsDepartment(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->department()->getQuery();

            if (!$query->exists()) {
                $fail('validation.exists')->translate();
            }
        };
    }

    private function ruleUniqueNamePerDepartment(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) {
            $query = $this->newQuery()
                ->where('department_id', $this->getAttribute('department_id'))
                ->where('name', $value)
            ;

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail('validation.unique')->translate();
            }
        };
    }

    private function ruleUniqueCode(string $column): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) use ($column) {
            $query = $this->newQuery()->where($column, $value);

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail('validation.unique')->translate();
            }
        };
    }
}
