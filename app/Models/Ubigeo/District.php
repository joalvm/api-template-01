<?php

namespace App\Models\Ubigeo;

use App\Models\Model;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

/**
 * @property      int        $id
 * @property      int        $province_id
 * @property      string     $name
 * @property      string     $code
 * @property      float|null $latitude
 * @property      float|null $longitude
 * @property-read Province   $province
 */
class District extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'public.districts';

    protected $fillable = [
        'province_id',
        'name',
        'code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'province_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function rules(): array
    {
        return [
            'province_id' => [
                'required',
                'integer',
                new IntegerPositiveRule(),
                $this->ruleExistsProvince(),
            ],
            'name' => ['required', 'string', $this->ruleUniqueNamePerProvince()],
            'code' => ['required', 'string', 'size:6', $this->ruleUniqueCode()],
            'latitude' => ['nullable', 'numeric', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'required_with:latitude'],
        ];
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    private function ruleExistsProvince(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->province()->getQuery();

            if (!$query->exists()) {
                $fail(Lang::get('validation.exists', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleUniqueCode(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) {
            $query = $this->newQuery()->where('code', $value);

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleUniqueNamePerProvince(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) {
            $query = $this->newQuery()
                ->where('province_id', $this->getAttribute('province_id'))
                ->where('name', $value)
            ;

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }
}
