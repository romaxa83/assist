<?php

namespace App\Core\Permissions\Permissions\Tag;

use App\Core\Permissions\Permissions\BasePermission;

final readonly class TagDeletePermission extends BasePermission
{
    public const KEY = TagPermissionsGroup::KEY . '.delete';
}
