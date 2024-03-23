<?php

namespace App\Repositories;

use App\Components\Managers\UserManager;
use App\Interfaces\BaseInterface;

abstract class Repository implements BaseInterface
{
    /**
     * Información del de la session.
     */
    protected UserManager $user;

    public function loadUser(UserManager $user): void
    {
        $this->user = $user;

        foreach (get_object_vars($this) as $propertyName => $value) {
            if ($value instanceof BaseInterface) {
                $this->{$propertyName}->loadUser($user);
            }
        }
    }
}
