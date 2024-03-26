<?php

namespace App\Exceptions\Users;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\ForbiddenException;

class CannotDeleteSelfUserException extends ForbiddenException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.user.cannot_delete_self_user'));
    }
}
