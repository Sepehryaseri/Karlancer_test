<?php

namespace App\Enums;

enum UserActivationStatus: string
{
    case ACTIVE = '1';
    case INACTIVE = '0';
}
