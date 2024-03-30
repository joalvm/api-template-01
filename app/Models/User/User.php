<?php

namespace App\Models\User;

use App\Enums\UserRole;
use App\Facades\Session;
use App\Facades\User as FacadesUser;
use App\Models\Model;
use App\Models\Person;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Enum;
use Joalvm\Utils\Casts\TimestamptzCast;
use Joalvm\Utils\Rules\TimestamptzRule;

/**
 * @property      int                            $id
 * @property      int                            $person_id
 * @property      UserRole                       $role
 * @property      string                         $email
 * @property      string                         $password
 * @property      string                         $salt
 * @property      string|null                    $avatar_url
 * @property      string|null                    $verification_token
 * @property      \Carbon\Carbon|null            $verified_at
 * @property      string|null                    $password_reset_token
 * @property      \Carbon\Carbon|null            $password_reset_at
 * @property      \Carbon\Carbon|null            $login_at
 * @property      bool                           $enabled
 * @property      bool                           $super_admin
 * @property      Person                         $person
 * @property-read Collection<array-key, Session> $sessions
 */
class User extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    /**
     * Mantiene la contraseña real del usuario al momento de la creación o actualización.
     */
    public ?string $realPassword = null;

    public bool $isEmailChanged = false;

    protected $table = 'public.users';

    protected $fillable = [
        'person_id',
        'role',
        'email',
        'password',
        'salt',
        'avatar_url',
        'verification_token',
        'verified_at',
        'password_reset_token',
        'password_reset_at',
        'super_admin',
        'login_at',
        'enabled',
    ];

    protected $attributes = [
        'enabled' => true,
        'role' => UserRole::USER,
        'super_admin' => false,
    ];

    protected $casts = [
        'person_id' => 'integer',
        'role' => UserRole::class,
        'verified_at' => TimestamptzCast::class,
        'password_reset_at' => TimestamptzCast::class,
        'login_at' => TimestamptzCast::class,
        'enabled' => 'boolean',
    ];

    public function rules(): array
    {
        return [
            'person_id' => [
                'required',
                'integer',
                new IntegerPositiveRule(),
                $this->ruleExistsPerson(),
                $this->ruleUniquePersonId(),
            ],
            'role' => [
                'required',
                new Enum(UserRole::class),
                $this->checkSessionRole(),
            ],
            'email' => ['required', 'email', $this->ruleUniqueEmail()],
            'password' => ['required', 'string'],
            'salt' => ['required', 'string', 'size:16'],
            'avatar_url' => ['nullable', 'string'],
            'verification_token' => ['nullable', 'string'],
            'verified_at' => ['nullable', new TimestamptzRule()],
            'password_reset_token' => ['nullable', 'string'],
            'password_reset_at' => ['nullable', new TimestamptzRule()],
            'login_at' => ['nullable', new TimestamptzRule()],
            'enabled' => ['required', 'boolean'],
            'super_admin' => ['required', 'boolean'],
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id', 'id');
    }

    public function setRealPassword(string $password): self
    {
        $this->realPassword = $password;

        return $this;
    }

    public function getRealPassword(): ?string
    {
        return $this->realPassword;
    }

    private function ruleExistsPerson(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if (!$this->person()->exists()) {
                $fail(Lang::get('validation.exists', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleUniquePersonId(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->query()->where('person_id', $value);

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleUniqueEmail(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->query()->where('email', $value);

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }

    private function checkSessionRole(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if (FacadesUser::isUser()) {
                $fail(Lang::get('exception.users.create_not_allowed'));
            }
        };
    }
}
