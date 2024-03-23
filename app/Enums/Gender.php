<?php

namespace App\Enums;

enum Gender: string
{
    use Enum;

    case FEMALE = 'FEMALE';

    case MALE = 'MALE';
}
