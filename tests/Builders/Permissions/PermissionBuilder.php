<?php

namespace Tests\Builders\Permissions;

use App\Core\Permissions\Models\Permission;
use Tests\Builders\BaseBuilder;

class PermissionBuilder extends BaseBuilder
{
    function modelClass(): string
    {
        return Permission::class;
    }

    public function name(string $value): self
    {
        $this->data['name'] = $value;
        return $this;
    }
}

