<?php

namespace App\Exceptions\Auth;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class InvalidTokenException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, Lang::get('api.auth.invalid_token'));
    }
}
