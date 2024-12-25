<?php

namespace Tests\Builders\Permissions;

use App\Core\Permissions\Models\Role;
use Tests\Builders\BaseBuilder;

class RoleBuilder extends BaseBuilder
{
    function modelClass(): string
    {
        return Role::class;
    }
}

