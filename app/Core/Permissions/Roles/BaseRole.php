<?php

namespace App\Core\Permissions\Roles;

abstract readonly class BaseRole  implements DefaultRole
{
    public function getName(): string
    {
        return static::NAME;
    }
}





