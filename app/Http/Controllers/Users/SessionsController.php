<?php

namespace App\Http\Controllers\Users;

use App\DataObjects\Repositories\Users\LoginSessionData;
use App\Facades\Session;
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
            'remember' => $request->input('remember', false),
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
        return Response::item($this->repository->profile());
    }

    public function logout(): JsonResponse
    {
        return Response::destroyed(
            $this->repository->logout(Session::id())
        );
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
