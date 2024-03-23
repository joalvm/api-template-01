<?php

namespace App\Models\Ubigeo;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property      int                             $id
 * @property      string                          $name
 * @property      string                          $code
 * @property      float|null                      $latitude
 * @property      float|null                      $longitude
 * @property-read Collection<array-key, Province> $provinces
 */
class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'public.departments';

    protected $fillable = [
        'name',
        'code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', $this->unique('name')],
            'code' => ['required', 'string', 'size:2', $this->unique('code')],
            'latitude' => ['nullable', 'numeric', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'required_with:latitude'],
        ];
    }

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'department_id', 'id');
    }

    public function unique(string $column): \Closure
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
