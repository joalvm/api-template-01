<?php

namespace App\Http\Controllers\Ubigeo;

use App\DataObjects\Repositories\Ubigeo\CreateDistrictData;
use App\DataObjects\Repositories\Ubigeo\UpdateDistrictData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubigeo\StoreDistrictRequest;
use App\Http\Requests\Ubigeo\UpdateDistrictRequest;
use App\Interfaces\Ubigeo\DistrictsInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Joalvm\Utils\Facades\Response;

class DistrictsController extends Controller
{
    public function __construct(protected DistrictsInterface $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return Response::collection(
            $this->repository
                ->setCodes($request->get('codes'))
                ->setProvinces($request->get('provinces'))
                ->setProvinceCodes($request->get('province_codes'))
                ->setDepartments($request->get('departments'))
                ->setDepartmentCodes($request->get('department_codes'))
                ->all()
        );
    }

    public function store(StoreDistrictRequest $request): JsonResponse
    {
        $data = CreateDistrictData::from($request->post());

        return Response::stored(
            $this->repository->find(
                $this->repository->create($data)->id
            )
        );
    }

    public function show($id): JsonResponse
    {
        return Response::item($this->repository->find($id));
    }

    public function update($id, UpdateDistrictRequest $request): JsonResponse
    {
        $data = UpdateDistrictData::from($request->post());

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
