<?php

namespace App\Exceptions\Users;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\ForbiddenException;

class CannotDeleteSuperAdminException extends ForbiddenException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.users.cannot_delete_super_admin'));
    }
}
