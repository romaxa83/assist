<?php

namespace App\Enums;

use App\Core\Enums\Traits\InvokableCases;

/**
 * @method static FRONT()
 */

enum DateFormat: string
{
    use InvokableCases;

    case FRONT = 'Y-m-d H:i';
}