<?php

namespace App\Exceptions\Auth;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\NotFoundException;

class SessionNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(Lang::get('exception.session.not_found'));
    }
}
