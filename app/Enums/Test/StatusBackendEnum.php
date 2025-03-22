<?php

namespace App\Enums\Test;


use App\Core\Enums\Traits\Comparable;


enum StatusBackendEnum: string
{
    use Comparable;

    case Draft = 'draft';
    case New = 'new';
    case Closed = 'closed';
}