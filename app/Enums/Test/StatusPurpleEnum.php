<?php

namespace App\Enums\Test;

use App\Core\Enums\Traits\InvokableCases;

enum StatusPurpleEnum
{
    use InvokableCases;

    case Draft;
    case New ;
    case Closed;
}