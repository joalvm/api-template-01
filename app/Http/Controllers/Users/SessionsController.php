<?php

namespace App\Http\Controllers\Users;

use App\DataObjects\Repositories\Users\LoginSessionData;
use App\Facades\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Interfaces\Users\SessionsInterface;
use Illuminate\Http\JsonResponse;
use Jenssegers\Agent\Agent;
use Joalvm\Utils\Facades\Response;

class SessionsController extends Controller
{
    public function __construct(protected SessionsInterface $repository)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $agent = new Agent($request->headers->all(), $request->userAgent());

        $data = LoginSessionData::from([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'rememberMe' => $request->input('remember_me', false),
            'ip' => $request->ip(),
            'browser' => $this->browser($agent),
            'browserVersion' => $this->browserVersion($agent),
            'platform' => $this->platform($agent),
            'platformVersion' => $this->platformVersion($agent),
            'host' => $request->getHost(),
        ]);

        return Response::item($this->repository->login($data));
    }

    public function profile(): JsonResponse
    {
        return Response::item($this->repository->profile(User::id()));
    }

    public function logout(): JsonResponse
    {
        return Response::destroyed($this->repository->logout(User::id()));
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
}
