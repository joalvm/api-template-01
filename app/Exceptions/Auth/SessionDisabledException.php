<?php

namespace App\Exceptions\Auth;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class SessionDisabledException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_UNAUTHORIZED, Lang::get('api.auth.session.disabled'));
    }
}