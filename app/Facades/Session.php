<?php

// phpcs:disable Generic.Files.LineLength

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void                authenticate(int $sessionId) inicia la autenticación de un usuario.
 * @method static int                 id()                         Obtiene el id de la session.
 * @method static bool                isAuthenticated()            Si la sesión está autenticada.
 * @method static bool                isSuperAdmin()               Si la session es de un super administrador.
 * @method static bool                isAdmin()                    Si la sesión es de un administrador.
 * @method static bool                isUserBasic()                Si la sesión es de un usuario común.
 * @method static \App\Enums\UserRole userRole()                   el tipo de usuario de la sesión.
 */
class Session extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'app.session';
    }
}
