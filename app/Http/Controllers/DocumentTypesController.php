<?php

namespace App\Http\Controllers;

use App\Facades\Session;
use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Interfaces\DocumentTypesInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Joalvm\Utils\Exceptions\ForbiddenException;
use Joalvm\Utils\Facades\Response;

class DocumentTypesController extends Controller
{
    public function __construct(protected DocumentTypesInterface $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return Response::collection(
            $this->repository
                ->setCharType($request->get('char_type'))
                ->setLengthTypes($request->get('length_types'))
                ->all()
        );
    }

    public function show($id): JsonResponse
    {
        return Response::item($this->repository->find($id));
    }

    public function store(StoreDocumentTypeRequest $request): JsonResponse
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

    public function update($id, UpdateDocumentTypeRequest $request): JsonResponse
    {
        if (!Session::isSuperAdmin()) {
            throw new ForbiddenException();
        }

        return Response::stored(
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
