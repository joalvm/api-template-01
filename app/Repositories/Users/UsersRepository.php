<?php

namespace App\Repositories\Users;

use App\Components\JWT;
use App\Enums\UserRole;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\Users\AlreadyVerifiedUserException;
use App\Exceptions\Users\WrongCurrentPasswordException;
use App\Interfaces\PersonsInterface;
use App\Interfaces\Users\UsersInterface;
use App\Models\User\User;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Collection;
use Joalvm\Utils\Item;

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

    /**
     * @var int[]
     */
    private array $clients = [];

    public function __construct(
        public User $model,
        protected PersonsInterface $personsRepository
    ) {
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

    public function save(array $data): User
    {
        $only = ['role', 'person_id', 'email', 'password', 'avatar_url'];

        $model = $this->model->newInstance(Arr::only($data, $only));

        if ($this->user->isSuperAdmin()) {
            $model->setAttribute('super_admin', Arr::get($data, 'super_admin', false));
        }

        DB::beginTransaction();

        list($password, $salt) = $this->encryptPassword($model->getAttribute('password'));

        $model->setAttribute('password', $password);
        $model->setAttribute('salt', $salt);

        $this->handleVerificationToken($model);

        if (Arr::has($data, 'person')) {
            $personData = Arr::get($data, 'person');

            if (!Arr::has($personData, 'email')) {
                $personData['email'] = $model->getAttribute('email');
            }

            $model->setAttribute(
                'person_id',
                $this->personsRepository->save($personData)->id
            );
        }

        $model->validate()->save();

        DB::commit();

        return $model;
    }

    public function update($id, array $data): User
    {
        $model = $this->getModel($id);

        // 1. Verificar si la contraseña a cambiado.
        if (Arr::has($data, 'password')) {
            $this->changePasswordIfCurrentIsValid(
                $model,
                Arr::get($data, 'current_password'),
                Arr::get($data, 'password')
            );
        }

        // 2. Verificar si el correo es diferente.
        if (Arr::has($data, 'email')) {
            $model->setAttribute('verified_at', null);

            $this->generateVerificationToken($model);
        }

        $model->fill(Arr::only($data, ['email', 'enabled', 'avatar_url']));

        $model->validate()->update();

        return $model;
    }

    public function delete($id): bool
    {
        $model = $this->getModel($id);

        DB::beginTransaction();

        if ($result = $model->delete()) {
            $model->clients()->delete();
        }

        DB::commit();

        return $result;
    }

    public function getModel($id): User
    {
        return $this->model->newQuery()->findOrFail(to_int($id));
    }

    public function getModelByPerson(int $personId): User
    {
        $model = $this->model->newQuery()->where('person_id', $personId)->first();

        if (!$model) {
            throw new ResourceNotFoundException('User');
        }

        return $model;
    }

    public function getModelByEmail(string $email): User
    {
        $model = $this->model->newQuery()->where('email', $email)->first();

        if (!$model) {
            throw new ResourceNotFoundException('User');
        }

        return $model;
    }

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

    public function setClients($clients): static
    {
        $this->clients = to_list_int($clients);

        return $this;
    }

    public function isValidPassword(User $model, string $password): bool
    {
        return Hash::check(
            "{$model->salt}.{$password}",
            $model->password,
            ['rounds' => 14]
        );
    }

    private function changePasswordIfCurrentIsValid(
        User &$model,
        string $currentPassword,
        string $newPassword = null
    ): void {
        if (!$this->isValidPassword($model, $currentPassword)) {
            throw new WrongCurrentPasswordException();
        }

        list($encryptedPassword, $salt) = $this->encryptPassword($newPassword);

        $model->setAttribute('password_reset_at', Carbon::now());
        $model->setAttribute('password', $encryptedPassword);
        $model->setAttribute('salt', $salt);
    }

    /**
     * Cifrando la contraseña y generando el salt.
     *
     * @return array<string>
     */
    private function encryptPassword(string $password = null): array
    {
        $password = $password ?: User::DEFAULT_PASSWORD;
        $salt = Str::random();

        return [Hash::make("{$salt}.{$password}", ['rounds' => 14]), $salt];
    }

    private function handleVerificationToken(User &$model): void
    {
        if (!is_null($model->verified_at)) {
            throw new AlreadyVerifiedUserException();
        }

        $this->generateVerificationToken($model);

        $model->update();
    }

    private function generateVerificationToken(User &$model): void
    {
        $payload = [
            'iss' => request()->getHost(),
            'sub' => $model->id,
        ];

        list($token) = JWT::encode($payload, 48 * 60);

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
