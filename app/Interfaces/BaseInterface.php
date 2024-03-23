<?php

namespace App\Interfaces;

use App\Components\Managers\UserManager;

interface BaseInterface
{
    /**
     * Carga la session de un usuario al repositorio.
     */
    public function loadUser(UserManager $userManager): void;
}
