<?php

namespace App\Repositories\Users;

use App\Components\Functions;
use App\DataObjects\Repositories\Users\CreateSessionData;
use App\DataObjects\Repositories\Users\LoginSessionData;
use App\Exceptions\Auth\SessionDisabledException;
use App\Exceptions\Auth\SessionNotFoundException;
use App\Exceptions\Auth\WrongAuthException;
use App\Interfaces\Users\SessionsInterface;
use App\Interfaces\Users\UsersInterface;
use App\Models\User\Session;
use App\Models\User\User;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Exceptions\UnauthorizedException;
use Joalvm\Utils\Item;
use Joalvm\Utils\JWT;

class SessionsRepository extends Repository implements SessionsInterface
{
    public function __construct(
        public Session $model,
        protected UsersInterface $usersRepository
    ) {
    }

    public function save(CreateSessionData $data): Session
    {
        $model = $this->model->newInstance($data->all());

        $model->validate()->save();

        return $model;
    }

    public function profile(): ?Item
    {
        return Builder::table('public.persons', 'p')
            ->schema($this->profileSchema())
            ->join('public.document_types as dt', 'dt.id', 'p.document_type_id')
            ->join('public.users as u', 'u.person_id', 'p.id')
            ->where('u.id', $this->user->id())
            ->whereNull(['p.deleted_at', 'u.deleted_at'])
            ->first()
        ;
    }

    public function login(LoginSessionData $data, bool $validatePassword = true): Item
    {
        $userModel = $this->getUserInfo($data->email, $data->password, $validatePassword);

        DB::beginTransaction();

        $sessionModel = $this->save(
            $this->getSessionData($userModel->id, $data)
        );

        $this->handleJwtToken($sessionModel, $userModel, $data->host);

        DB::commit();

        return new Item([
            'token' => $sessionModel->token,
            'expire_at' => $sessionModel->expire_at->format(\DateTime::ATOM),
        ]);
    }

    public function logout(int $sessionId): bool
    {
        $model = $this->model->newQuery()->findOrFail($sessionId);

        return $model->setAttribute('closed_at', Carbon::now())->update();
    }

    /**
     * Autentica una session.
     *
     * @throws UnauthorizedException
     */
    public function authenticate(int $sessionId): array
    {
        $auth = Functions::call('public.fn_user_authenticate')
            ->paramInt('session_id', $sessionId)
            ->getJson()
        ;

        if (is_null($auth)) {
            throw new SessionNotFoundException();
        }

        return $auth;
    }

    private function getSessionData(
        int $userId,
        LoginSessionData $data,
    ): CreateSessionData {
        $now = Carbon::now();

        $now->addDays($data->rememberMe ? 30 : 1);

        return CreateSessionData::from([
            'user_id' => $userId,
            'token' => Str::random(),
            'expire_at' => $now->format(\DateTime::ATOM),
            'ip' => $data->ip,
            'browser' => $data->browser,
            'browser_version' => $data->browserVersion,
            'platform' => $data->platform,
            'platform_version' => $data->platformVersion,
        ]);
    }

    private function handleJwtToken(
        Session $sessionModel,
        User $userModel,
        string $host
    ): void {
        $payload = [
            'jti' => $sessionModel->id,
            'iss' => $host,
            'isa' => $userModel->super_admin,
            'sub' => $userModel->id,
            'rol' => $userModel->role,
        ];

        /** @var Carbon */
        $expire = SupportCarbon::createFromImmutable($sessionModel->expire_at);

        $encoded = JWT::encode(
            $payload,
            $expire->diffInMinutes(Carbon::parse(LARAVEL_START))
        );

        $sessionModel->setAttribute('token', $encoded[0]);

        $sessionModel->update();
    }

    private function getUserInfo(
        string $email,
        string $password,
        bool $validatePassword = true
    ): User {
        $userModel = $this->validateUserEmail($email);

        if (!$validatePassword) {
            return $userModel;
        }

        // Verificando contraseña ingresada.
        if (!$this->usersRepository->isValidPassword($userModel, $password)) {
            throw new WrongAuthException();
        }

        if (!$userModel->enabled) {
            throw new SessionDisabledException();
        }

        return $userModel;
    }

    private function validateUserEmail(string $email): User
    {
        try {
            $model = $this->usersRepository->getModelByEmail(
                mb_strtolower($email, 'UTF-8')
            );
        } catch (\Throwable $ex) {
            throw new WrongAuthException();
        }

        return $model;
    }

    private function profileSchema(): array
    {
        return [
            'id',
            'names',
            'last_names',
            'gender',
            'id_document',
            'email',
            'document_type:dt' => [
                'id',
                'name',
                'abbr',
                'length_type',
                'length',
                'char_type',
            ],
            'user:u' => [
                'id',
                'role',
                'email',
                'avatar_url',
                'enabled',
                'login_at',
                'password_reset_at',
                'super_admin',
                'is_verified' => DB::raw('u.verified_at IS NULL'),
            ],
        ];
    }
}
