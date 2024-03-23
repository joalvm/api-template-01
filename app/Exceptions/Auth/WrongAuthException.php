<?php

namespace App\Exceptions\Auth;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

/**
 * El usuario no ha ingresado su contraseña o usuario correctamente.
 *
 * {@inheritDoc}
 */
class WrongAuthException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_UNAUTHORIZED, Lang::get('api.session.wrong'));
    }
}
