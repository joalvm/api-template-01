<?php

namespace App\Exceptions\Auth;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class TokenExpiredException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_UNAUTHORIZED, Lang::get('api.auth.token_expired'));
    }
}
