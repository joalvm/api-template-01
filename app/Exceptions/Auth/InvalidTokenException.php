<?php

namespace App\Exceptions\Auth;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\BadRequestException;

class InvalidTokenException extends BadRequestException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.auth.invalid_token'));
    }
}
