<?php

namespace App\Exceptions\Users;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\NotAcceptableException;

class AlreadyVerifiedUserException extends NotAcceptableException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exceptions.users.already_verified'));
    }
}
