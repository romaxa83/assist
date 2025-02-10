<?php

namespace App\Enums;

use App\Core\Enums\Traits\InvokableCases;

/**
 * @method static FRONT()
 * @method static FRONT_FILTER()
 */

enum DateFormat: string
{
    use InvokableCases;

    case FRONT = 'Y-m-d H:i';
    case FRONT_FILTER = 'Y-m-d';
}