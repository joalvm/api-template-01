<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResourceNotFoundException extends HttpException
{
    public $code = Response::HTTP_NOT_FOUND;

    public function __construct(string $resourceName)
    {
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            Lang::get('api.resource.not_found', ['name' => Str::pluralStudly($resourceName)])
        );
    }
}
