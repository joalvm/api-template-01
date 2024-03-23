<?php

namespace App\Http\Controllers\Users;

use App\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Interfaces\Users\UsersInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Joalvm\Utils\Exceptions\ForbiddenException;
use Joalvm\Utils\Facades\Response;

class UsersController extends Controller
{
    public function __construct(protected UsersInterface $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return Response::collection(
            $this->repository
                ->setPersons($request->get('persons'))
                ->setRoles($request->get('roles'))
                ->all()
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        if (Session::isUserBasic()) {
            throw new ForbiddenException();
        }

        return Response::stored(
            $this->repository->find(
                $this->repository->save($request->all())->id
            )
        );
    }

    public function show($id): JsonResponse
    {
        return Response::item($this->repository->find($id));
    }

    public function update($id, UpdateUserRequest $request): JsonResponse
    {
        return Response::updated(
            $this->repository->find(
                $this->repository->update($id, $request->all())->id
            )
        );
    }

    public function destroy($id): JsonResponse
    {
        if (Session::isUserBasic()) {
            throw new ForbiddenException();
        }

        return Response::destroyed($this->repository->delete($id));
    }
}
