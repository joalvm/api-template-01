<?php

namespace App\Exceptions\Auth;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\UnauthorizedException;

/**
 * El usuario no ha ingresado su contraseña o usuario correctamente.
 *
 * {@inheritDoc}
 */
class WrongAuthException extends UnauthorizedException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.session.wrong'));
    }
}
