<?php

return [
    'resource' => [
        'with_children' => 'You cannot delete :resource because it is assigned to :count :children',
    ],
    'users' => [
        'wrong_current_password' => 'The current password does not match.',
        'already_verified' => 'The user is already verified.',
        'create_not_allowed' => 'You do not have permissions to create this type of user',
        'cannot_delete_super_admin' => 'You cannot delete this type of user.',
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
];
