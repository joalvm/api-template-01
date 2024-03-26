<?php

namespace App\Enums;

use Joalvm\Utils\Traits\ExtendsEnums;

enum CharType: string
{
    use ExtendsEnums;

    case NUMERIC = 'NUMERIC';

    case ALPHA_NUMERIC = 'ALPHA_NUMERIC';
}
