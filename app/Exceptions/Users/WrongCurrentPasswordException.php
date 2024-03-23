<?php

namespace App\Exceptions\Users;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\NotAcceptableException;

class WrongCurrentPasswordException extends NotAcceptableException
{
    public function __construct()
    {
        parent::__construct(
            Lang::get('exceptions.users.wrong_current_password')
        );
    }
}
