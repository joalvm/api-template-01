<?php

namespace App\Exceptions\Auth;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class SessionNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_NOT_FOUND, Lang::get('api.auth.session.not_found'));
    }
}
