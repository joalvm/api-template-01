<?php

return [
    'resource' => [
        'not_found' => 'The resource :name does not exist or has already been deleted',
        'in_use' => 'The resource is assigned to another and cannot be deleted.',
    ],
    'session' => [
        'wrong' => 'Wrong email or password.',
        'not_found' => 'No session found.',
        'disabled' => 'Access to the platform has been disabled.',
    ],
    'auth' => [
        'invalid_token' => 'The authorization token is not valid.',
        'token_expired' => 'Authorization token has expired.',
        'token_not_found' => 'The resource requires an authorization token.',
    ],
    'user' => [
        'create_not_allowed' => 'You do not have permissions to create this type of user',
    ],
];
