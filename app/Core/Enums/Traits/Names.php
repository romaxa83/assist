<?php

declare(strict_types=1);

namespace App\Core\Enums\Traits;

trait Names
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }
}
