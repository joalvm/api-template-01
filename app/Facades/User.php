<?php

// phpcs:disable Generic.Files.LineLength

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void                    load(array $user)            Carga los datos del usuario.
 * @method static void                    loadFromUserId(int $userId)  Carga los datos del usuario a partir del id del usuario.
 * @method static int|null                id()                         Retorna el id del usuario.
 * @method static int|null                personId()                   Retorna el id de la persona asociada al usuario.
 * @method static UserRole|null           role()                       Retorna el rol del usuario.
 * @method static bool                    enabled()                    Retorna true si el usuario está habilitado.
 * @method static \Joalvm\Utils\Item|null person()                     Retorna la información de la persona asociada al usuario.
 * @method static \Joalvm\Utils\Item|null documentType()               Retorna la información del tipo de documento de la persona asociada al usuario.
 * @method static bool                    isSuperAdmin()               Retorna true si el usuario es super administrador.
 * @method static bool                    isAdmin()                    Retorna true si el usuario es administrador de cliente.
 * @method static bool                    isUser()                    Retorna true si el usuario es un usuario básico.
 */
class User extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'app.user';
    }
}
