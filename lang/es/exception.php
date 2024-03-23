<?php

return [
    'resource' => [
        'with_children' => 'No puedes eliminar :resource porque está asignado a :count :children',
    ],
    'users' => [
        'wrong_current_password' => 'La contraseña actual no coincide.',
        'already_verified' => 'El usuario ya se encuentra verificado.',
        'create_not_allowed' => 'No Tiene permisos para crear este tipo de usuario',
        'cannot_delete_super_admin' => 'No puedes eliminar a este tipo de usuario.',
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
];
