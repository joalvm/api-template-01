<?php

namespace App\Http\Controllers\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateDepartmentData;
use App\DataObjects\Repositories\Ubigeo\UpdateDepartmentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubigeo\StoreDepartmentRequest;
use App\Http\Requests\Ubigeo\UpdateDepartmentRequest;
use App\Interfaces\Ubigeo\DepartmentsInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $data = CreateDepartmentData::from($request->post());

        return Response::stored(
            $this->repository->find(
                $this->repository->save($data)->id
            )
        );
    }

    public function show($id): JsonResponse
    {
        return Response::item($this->repository->find($id));
    }

    public function update($id, UpdateDepartmentRequest $request): JsonResponse
    {
        $data = UpdateDepartmentData::from($request->post());

        return Response::updated(
            $this->repository->find(
                $this->repository->update($id, $data)->id
            )
        );
    }

    public function destroy($id): JsonResponse
    {
        return Response::destroyed($this->repository->delete($id));
    }
}
