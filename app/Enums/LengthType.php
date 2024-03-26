<?php

namespace App\Enums;

use Joalvm\Utils\Traits\ExtendsEnums;

enum LengthType: string
{
    use ExtendsEnums;

    case MAX = 'MAX';

    case MIN = 'MIN';

    case EXACT = 'EXACT';
}
