<?php

namespace App\Repositories\Users;

use App\DataObjects\Repositories\Users\CreateUserData;
use App\DataObjects\Repositories\Users\UpdateUserData;
use App\DataObjects\Repositories\Users\UpdateUserEmailData;
use App\DataObjects\Repositories\Users\UpdateUserPasswordData;
use App\Enums\UserRole;
use App\Exceptions\Users\AlreadyVerifiedUserException;
use App\Exceptions\Users\WrongCurrentPasswordException;
use App\Interfaces\Users\UsersInterface;
use App\Models\User\User;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;
use Joalvm\Utils\JWT;

class UsersRepository extends Repository implements UsersInterface
{
    /**
     * @var int[]
     */
    private array $persons = [];

    /**
     * @var UserRole[]
     */
    private array $roles = [];

    public function __construct(public User $model)
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

    public function findByEmail(string $email): ?Item
    {
        return $this->builder()->where('u.email', $email)->first();
    }

    public function save(CreateUserData $data): User
    {
        $model = $this->model->newInstance($data->except('password')->all());

        if (is_optional($data->password) or is_null($data->password)) {
            $data->password = Str::password(8);
        }

        $this->handlePassword($data->password, $model);
        $this->handleVerificationToken($model);

        $model->validate()->save();

        return $model;
    }

    public function update($id, UpdateUserData $data): User
    {
        $model = $this->getModel($id);

        $model->fill($data->all());

        $model->validate()->update();

        return $model;
    }

    public function updateEmail($id, UpdateUserEmailData $data): User
    {
        $model = $this->getModel($id);

        if ($model->email === $data->email) {
            return $model;
        }

        $model->setAttribute('email', $data->email);

        $this->generateVerificationToken($model);

        $model->update();

        $model->isEmailChanged = true;

        return $model;
    }

    public function updatePassword($id, UpdateUserPasswordData $data): User
    {
        $model = $this->getModel($id);

        if (!$this->isValidPassword($model, $data->currentPassword)) {
            throw new WrongCurrentPasswordException();
        }

        if ($data->password !== $data->confirmPassword) {
            throw new WrongCurrentPasswordException();
        }

        list($hash, $salt) = $this->encryptPassword($data->password);

        $model->setAttribute('password_reset_at', Carbon::now());
        $model->setAttribute('password', $hash);
        $model->setAttribute('salt', $salt);

        $model->update();

        return $model;
    }

    public function delete($id): bool
    {
        $model = $this->getModel($id);

        if ($result = $model->delete()) {
            $model
                ->sessions()
                ->where('closed_at', null)
                ->update(['closed_at' => Carbon::now()])
            ;
        }

        return $result;
    }

    public function getModel($id): User
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function getModelByPerson(int $personId): User
    {
        return $this->model
            ->newQuery()
            ->where('person_id', $personId)
            ->firstOrFail()
        ;
    }

    public function getModelByEmail(string $email): User
    {
        return $this->model
            ->newQuery()
            ->where('email', $email)
            ->firstOrFail()
        ;
    }

    /**
     * Obtiene los datos de autenticación del usuario usando su ID.
     */
    public function getAuthData(int $userId): array
    {
        $query = DB::table('public.users', 'u')
            ->selectRaw("
                to_jsonb(u) || jsonb_build_object(
                    'person', to_jsonb(p) || jsonb_build_object(
                        'document_type', to_jsonb(dt)
                    ),
                ) as data
            ")
            ->join('public.persons as p', 'p.id', 'u.person_id')
            ->join('public.document_types as dt', 'dt.id', 'p.document_type_id')
            ->where('u.id', $userId)
            ->whereNull(['u.deleted_at', 'p.deleted_at'])
        ;

        return json_decode($query->first()?->data ?? '[]', true);
    }

    public function setPersons($persons): static
    {
        $this->persons = to_list_int($persons);

        return $this;
    }

    public function setRoles($roles): static
    {
        $this->roles = to_list($roles);

        return $this;
    }

    /**
     * Verifica si la contraseña es válida.
     */
    public function isValidPassword(User $model, string $password): bool
    {
        return Hash::check(
            "{$model->salt}.{$password}",
            $model->password,
            ['rounds' => 14]
        );
    }

    /**
     * Cifra la contraseña y el salt, y los asigna al modelo.
     */
    private function handlePassword(string $password, User &$model): void
    {
        list($hash, $salt) = $this->encryptPassword($password);

        $model->setAttribute('password', $hash);
        $model->setAttribute('salt', $salt);

        $model->setRealPassword($password);
    }

    /**
     * Cifra la contraseña, genera un salt y lo retorna.
     *
     * @return array<string,string>
     */
    private function encryptPassword(?string $password = null): array
    {
        $salt = Str::random();
        $hash = Hash::make("{$salt}.{$password}", ['rounds' => 14]);

        return [$hash, $salt];
    }

    /**
     * Genera un token de verificación, lo asigna al modelo y lo actualiza.
     */
    private function handleVerificationToken(User &$model): void
    {
        if (!is_null($model->verified_at)) {
            throw new AlreadyVerifiedUserException();
        }

        $this->generateVerificationToken($model);

        $model->update();
    }

    /**
     * Genera un token de verificación y lo asigna al modelo.
     */
    private function generateVerificationToken(User &$model): void
    {
        $payload = ['sub' => $model->id];

        list($token) = JWT::encode($payload, 60);

        $model->setAttribute('verified_at', null);
        $model->setAttribute('verification_token', $token);
    }

    private function builder(): Builder
    {
        return $this->filters(
            Builder::table('public.users', 'u')
                ->schema($this->schema())
                ->join('public.persons as p', 'p.id', 'u.person_id')
                ->join('public.document_types as dt', 'dt.id', 'p.document_type_id')
        )->whereNull(['u.deleted_at', 'p.deleted_at']);
    }

    private function filters(Builder $builder): Builder
    {
        if ($this->persons) {
            $builder->whereIn('person_id', $this->persons);
        }

        if ($this->roles) {
            $builder->whereIn('u.role', $this->roles);
        }

        return $builder;
    }

    private function schema(): array
    {
        return [
            'id',
            'role',
            'email',
            'avatar_url',
            'verified_at',
            'password_reset_at',
            'login_at',
            'enabled',
            'super_admin',
            'person:p' => [
                'id',
                'names',
                'last_names',
                'gender',
                'email',
                'id_document',
                'document_type:dt' => [
                    'id',
                    'name',
                    'abbr',
                ],
            ],
            'created_at',
            'updated_at',
        ];
    }
}
