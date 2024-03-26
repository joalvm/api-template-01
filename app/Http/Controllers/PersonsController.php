<?php

namespace App\Http\Controllers;

use App\DataObjects\Repositories\CreatePersonData;
use App\DataObjects\Repositories\UpdatePersonData;
use App\Exceptions\Users\CannotDeleteSelfUserException;
use App\Facades\Session;
use App\Facades\User;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Interfaces\PersonsInterface;
use Illuminate\Http\JsonResponse;
use Joalvm\Utils\Exceptions\ForbiddenException;
use Joalvm\Utils\Facades\Response;

class PersonsController extends Controller
{
    public function __construct(protected PersonsInterface $repository)
    {
    }

    public function index(): JsonResponse
    {
        return Response::collection($this->repository->all());
    }

    public function store(StorePersonRequest $request): JsonResponse
    {
        $data = CreatePersonData::from($request->post());

        return Response::stored(
            $this->repository->find(
                $this->repository->save($data)->id
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
        $data = UpdatePersonData::from($request->post());

        return Response::updated(
            $this->repository->find(
                $this->repository->update($id, $data)->id
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
            throw new CannotDeleteSelfUserException();
        }

        return Response::destroyed($this->repository->delete($id));
    }
}
