<?php

namespace App\Core\Permissions\Permissions\Tag;

use App\Core\Permissions\Permissions\BasePermission;

final readonly class TagUpdatePermission extends BasePermission
{
    public const KEY = TagPermissionsGroup::KEY . '.update';
}
