<?php

namespace App\Enums;

use App\Core\Enums\Traits\InvokableCases;

/**
 * @method static ASC()
 * @method static DESC()
 */

enum OrderType: string
{
    use InvokableCases;

    case ASC = 'asc';
    case DESC = 'desc';
}