<?php

namespace App\Exceptions\Auth;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\UnauthorizedException;

class SessionDisabledException extends UnauthorizedException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.session.disabled'));
    }
}
