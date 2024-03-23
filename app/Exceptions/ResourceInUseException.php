<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

/**
 * El recurso no puede ser eliminado pues está siendo usado por otro.
 */
class ResourceInUseException extends HttpException
{
    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public $code = Response::HTTP_NOT_ACCEPTABLE;

    public function __construct()
    {
        parent::__construct(Response::HTTP_NOT_ACCEPTABLE, Lang::get('api.resource.in_use'));
    }
}
