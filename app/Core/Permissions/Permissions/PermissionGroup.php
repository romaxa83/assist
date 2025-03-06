<?php

namespace App\Core\Permissions\Permissions;

use App\Foundations\Modules\Permission\Permissions\Permission;
use Illuminate\Contracts\Support\Arrayable;

interface PermissionGroup extends Arrayable
{
    public function getKey(): string;

    public function getName(): string;

    /**
     * @return Permission[]
     */
    public function getPermissions(): array;

    public function getPosition(): int;
}

