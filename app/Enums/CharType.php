<?php

namespace App\Enums;

enum CharType: string
{
    use Enum;

    case NUMERIC = 'NUMERIC';

    case ALPHA_NUMERIC = 'ALPHA_NUMERIC';
}
