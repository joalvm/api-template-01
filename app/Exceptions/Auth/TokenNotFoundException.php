<?php

namespace App\Exceptions\Auth;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\UnauthorizedException;

class TokenNotFoundException extends UnauthorizedException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.auth.token_not_found'));
    }
}
