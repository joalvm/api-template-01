<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class DeletingInUseDocumentTypeException extends HttpException
{
    public function __construct(int $count)
    {
        parent::__construct(
            Response::HTTP_NOT_ACCEPTABLE,
            Lang::get('exceptions.document_type.in_use', ['count' => $count])
        );
    }
}
