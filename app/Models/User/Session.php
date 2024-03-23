<?php

namespace App\Models\User;

use App\Components\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Casts\TimestamptzCast;
use Joalvm\Utils\Rules\TimestamptzRule;

/**
 * @property      int                 $id
 * @property      int                 $user_id
 * @property      string              $token
 * @property      \Carbon\Carbon      $expire_at
 * @property      string              $ip
 * @property      string              $browser
 * @property      string              $browser_version
 * @property      string              $platform
 * @property      string              $platform_version
 * @property      \Carbon\Carbon|null $closed_at
 * @property-read User                $user
 */
class Session extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'public.user_sessions';

    protected $fillable = [
        'user_id',
        'token',
        'expire_at',
        'ip',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'closed_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'expire_at' => TimestamptzCast::class,
        'closed_at' => TimestamptzCast::class,
    ];

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                $this->ruleExistsUser(),
            ],
            'token' => [
                'required',
                'string',
                $this->ruleUniqueToken(),
            ],
            'expire_at' => ['required', new TimestamptzRule()],
            'ip' => ['required', 'string'],
            'browser' => ['required', 'string'],
            'browser_version' => ['required', 'string'],
            'platform' => ['required', 'string'],
            'platform_version' => ['required', 'string'],
            'closed_at' => ['nullable', new TimestamptzRule()],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    private function ruleExistsUser(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if (!$this->user()->exists()) {
                $fail(Lang::get('validation.exists', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleUniqueToken(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $builder = $this->newQuery()->where('token', $value);

            if ($this->exists) {
                $builder->where('id', '<>', $this->getAttribute('id'));
            }

            if ($builder->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }
}
