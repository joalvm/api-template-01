<?php

namespace App\Enums;

enum LengthType: string
{
    use Enum;

    case MAX = 'MAX';

    case MIN = 'MIN';

    case EXACT = 'EXACT';
}
