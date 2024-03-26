<?php

namespace App\Exceptions\Ubigeo;

use App\Exceptions\CannotDeleteResourceWithChildrenException;

class CannotDeleteDepartmentWithProvincesException extends CannotDeleteResourceWithChildrenException
{
    public function __construct(int $count)
    {
        parent::__construct(
            singular_name('department'),
            resource_name('province', $count),
            $count
        );
    }
}
