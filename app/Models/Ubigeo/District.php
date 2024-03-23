<?php

namespace App\Models\Ubigeo;

use App\Components\Model;
use App\Models\Client\Collaborator;
use App\Models\Incidence\Attention\Attention;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

/**
 * @property      int                                 $id
 * @property      int                                 $province_id
 * @property      string                              $name
 * @property      string                              $code
 * @property      float|null                          $latitude
 * @property      float|null                          $longitude
 * @property-read Province                            $province
 * @property-read Collection<array-key, Collaborator> $collaborators
 * @property-read Collection<array-key, Attention>    $attentions
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
                $this->ruleExistsProvince(),
            ],
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
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
}
