<?php

namespace App\DataObjects\Repositories\Users;

use Spatie\LaravelData\Data;

class UpdateUserEmailData extends Data
{
    /**
     * @param string $email New email.
     * @param string $host  Host que realiza la petición para el token de verificación.
     */
    public function __construct(
        public string $email,
        public string $host = '',
    ) {
    }
}
