<?php

namespace App\Http\Controllers;

use App\Facades\User;
use App\Interfaces\BaseInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function callAction($method, $parameters)
    {
        foreach (get_object_vars($this) as $propertyName => $value) {
            if ($value instanceof BaseInterface) {
                $this->{$propertyName}->loadUser(User::getFacadeRoot());
            }
        }

        return parent::callAction($method, $parameters);
    }
}
