<?php

namespace App\Http\Controllers\Users;

use App\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Interfaces\Users\SessionsInterface;
use Illuminate\Http\JsonResponse;
use Joalvm\Utils\Facades\Response;

class SessionsController extends Controller
{
    public function __construct(protected SessionsInterface $repository)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return Response::item($this->repository->login($request->all()));
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
}
