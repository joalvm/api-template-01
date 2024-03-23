<?php

namespace App\Repositories\Users;

use App\Components\Functions;
use App\Components\JWT;
use App\Exceptions\Auth\SessionDisabledException;
use App\Exceptions\Auth\SessionNotFoundException;
use App\Exceptions\Auth\WrongAuthException;
use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\Users\SessionsInterface;
use App\Interfaces\Users\UsersInterface;
use App\Models\User\Session;
use App\Models\User\User;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Joalvm\Utils\Builder;
use Joalvm\Utils\Exceptions\UnauthorizedException;
use Joalvm\Utils\Item;

class SessionsRepository extends Repository implements SessionsInterface
{
    public function __construct(
        public Session $model,
        protected UsersInterface $usersRepository
    ) {
    }

    public function save(array $data): Session
    {
        $model = $this->model->newInstance($data);

        $model->validate()->save();

        return $model;
    }

    public function profile(): Item
    {
        return Builder::table('public.persons', 'p')
            ->schema($this->profileSchema())
            ->join('public.document_types as dt', 'dt.id', 'p.document_type_id')
            ->join('public.users as u', 'u.person_id', 'p.id')
            ->where('u.id', $this->user->id())
            ->whereNull(['p.deleted_at', 'u.deleted_at'])
            ->casts(function (Item $item) {
                $item->jsonValues(['clients']);
            })
            ->getOne()
        ;
    }

    public function login(array $data, bool $validatePassword = true): Item
    {
        $userModel = $this->getUserInfo($data, $validatePassword);

        DB::beginTransaction();

        $sessionModel = $this->save(
            $this->getSessionData($data, $userModel->id)
        );

        $this->handleJwtToken($sessionModel, $userModel, request()->getHost());

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

    private function getSessionData(array $data, int $userId): array
    {
        $agent = new Agent(request()->headers->all(), request()->userAgent());
        $now = Carbon::now();

        $now->addDays(Arr::get($data, 'remember_me', false) ? 30 : 1);

        return [
            'user_id' => $userId,
            'token' => Str::random(),
            'expire_at' => $now->format(\DateTime::ATOM),
            'ip' => request()->ip(),
            'browser' => $this->browser($agent),
            'browser_version' => $this->browserVersion($agent),
            'platform' => $this->platform($agent),
            'platform_version' => $this->platformVersion($agent),
        ];
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

        if ($userModel->client_id) {
            $payload['cli'] = $userModel->client_id;
        }

        $encoded = JWT::encode($payload, $sessionModel->expire_at);

        $sessionModel->setAttribute('token', $encoded[0]);

        $sessionModel->update();
    }

    private function browser(Agent $agent): string
    {
        return $agent->browser() ?: 'unknown';
    }

    private function browserVersion(Agent $agent): string
    {
        return $agent->version($this->browser($agent)) ?: 'unknown';
    }

    private function platform(Agent $agent): string
    {
        return $agent->platform() ?: 'unknown';
    }

    private function platformVersion(Agent $agent): string
    {
        return $agent->version($this->platform($agent)) ?: 'unknown';
    }

    private function getUserInfo(array $data, bool $validatePassword = true): User
    {
        // Verificando la existencia del usuario
        $password = Arr::get($data, 'password');
        $userModel = $this->validateUserEmail(Arr::get($data, 'email'));

        if (!$validatePassword) {
            return $userModel;
        }

        // Verificando contraseña ingresada.
        if (!$this->usersRepository->isValidPassword($userModel, $password)) {
            throw new WrongAuthException();
        }

        return $userModel;
    }

    private function validateUserEmail(string $email): User
    {
        try {
            $model = $this->usersRepository->getModelByEmail(
                mb_strtolower($email, 'UTF-8')
            );
        } catch (ResourceNotFoundException $ex) {
            throw new WrongAuthException();
        }

        if (!$model->enabled) {
            throw new SessionDisabledException();
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
