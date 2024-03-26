<?php

namespace App\Http\Controllers\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateProvinceData;
use App\DataObjects\Repositories\Ubigeo\UpdateProvinceData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubigeo\StoreProvinceRequest;
use App\Http\Requests\Ubigeo\UpdateProvinceRequest;
use App\Interfaces\Ubigeo\ProvincesInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Joalvm\Utils\Facades\Response;

class ProvincesController extends Controller
{
    public function __construct(protected ProvincesInterface $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return Response::collection(
            $this->repository
                ->setCodes($request->get('codes'))
                ->setDepartments($request->get('departments'))
                ->setDepartmentCodes($request->get('department_codes'))
                ->all()
        );
    }

    public function store(StoreProvinceRequest $request): JsonResponse
    {
        $data = CreateProvinceData::from($request->post());

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

    public function update($id, UpdateProvinceRequest $request): JsonResponse
    {
        $data = UpdateProvinceData::from($request->post());

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
