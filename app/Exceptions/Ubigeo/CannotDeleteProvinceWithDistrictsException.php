<?php

namespace App\Exceptions\Ubigeo;

use App\Exceptions\CannotDeleteResourceWithChildrenException;

class CannotDeleteProvinceWithDistrictsException extends CannotDeleteResourceWithChildrenException
{
    public function __construct(int $count)
    {
        parent::__construct(
            singular_name('province'),
            resource_name('district', $count),
            $count
        );
    }
}
