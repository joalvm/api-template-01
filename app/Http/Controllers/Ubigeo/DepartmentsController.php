<?php

namespace App\Http\Controllers\Ubigeo;

use App\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubigeo\StoreDepartmentRequest;
use App\Http\Requests\Ubigeo\UpdateDepartmentRequest;
use App\Interfaces\Ubigeo\DepartmentsInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Joalvm\Utils\Exceptions\ForbiddenException;
use Joalvm\Utils\Facades\Response;

class DepartmentsController extends Controller
{
    public function __construct(protected DepartmentsInterface $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return Response::collection(
            $this->repository
                ->setCodes($request->get('codes'))
                ->all()
        );
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        if (!Session::isSuperAdmin()) {
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

    public function update($id, UpdateDepartmentRequest $request): JsonResponse
    {
        if (!Session::isSuperAdmin()) {
            throw new ForbiddenException();
        }

        return Response::updated(
            $this->repository->find(
                $this->repository->update($id, $request->all())->id
            )
        );
    }

    public function destroy($id): JsonResponse
    {
        if (!Session::isSuperAdmin()) {
            throw new ForbiddenException();
        }

        return Response::destroyed($this->repository->delete($id));
    }
}
