<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Lang;
use Joalvm\Utils\Exceptions\NotAcceptableException;

/**
 * El recurso no puede ser eliminado pues está siendo usado por otro.
 */
class CannotDeleteResourceWithChildrenException extends NotAcceptableException
{
    public function __construct(string $resource, string $children, int $count)
    {
        parent::__construct(
            Lang::get(
                'exception.resource.with_children',
                [
                    'resource' => $resource,
                    'children' => $children,
                    'count' => $count,
                ]
            )
        );
    }
}
