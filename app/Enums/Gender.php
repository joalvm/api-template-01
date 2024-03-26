<?php

namespace App\Enums;

use Joalvm\Utils\Traits\ExtendsEnums;

enum Gender: string
{
    use ExtendsEnums;

    case FEMALE = 'FEMALE';

    case MALE = 'MALE';
}
