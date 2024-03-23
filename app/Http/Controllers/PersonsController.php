<?php

namespace App\Http\Controllers;

use App\Facades\Session;
use App\Facades\User;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Interfaces\PersonsInterface;
use App\Interfaces\Users\UsersInterface;
use Illuminate\Http\JsonResponse;
use Joalvm\Utils\Exceptions\ForbiddenException;
use Joalvm\Utils\Facades\Response;

class PersonsController extends Controller
{
    public function __construct(
        protected PersonsInterface $repository,
        protected UsersInterface $usersRepository
    ) {
    }

    public function index(): JsonResponse
    {
        return Response::collection($this->repository->all());
    }

    public function store(StorePersonRequest $request): JsonResponse
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id): JsonResponse
    {
        return Response::item($this->repository->find($id));
    }

    /**
     * Actualiza un recurso.
     *
     * @param int $id
     */
    public function update($id, UpdatePersonRequest $request): JsonResponse
    {
        return Response::updated(
            $this->repository->find(
                $this->repository->update($id, $request->all())->id
            )
        );
    }

    /**
     * Elimina un recurso.
     *
     * @param int $id
     */
    public function destroy($id): JsonResponse
    {
        if (Session::isUserBasic()) {
            throw new ForbiddenException();
        }

        if ($id === User::id()) {
            throw new ForbiddenException('No puedes eliminar tu propia cuenta.');
        }

        return Response::destroyed($this->repository->delete($id));
    }
}
