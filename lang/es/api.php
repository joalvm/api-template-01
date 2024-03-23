<?php

return [
    'resource' => [
        'not_found' => 'El recurso :name no existe o ya fue eliminado.',
        'in_use' => 'El recurso está asignado a otro y no puede ser eliminado.',
    ],
    'session' => [
        'wrong' => 'Email o Contraseña incorrectos.',
        'not_found' => 'No se ha encontrado una sesión.',
        'disabled' => 'El acceso a la plataforma ha sido deshabilitado.',
    ],
    'auth' => [
        'invalid_token' => 'El token de autorización no es valido.',
        'token_expired' => 'El token de autorización ha expirado.',
        'token_not_found' => 'El recurso necesita un token de autorización.',
    ],
    'user' => [
        'create_not_allowed' => 'No Tiene permisos para crear este tipo de usuario',
    ],
];
