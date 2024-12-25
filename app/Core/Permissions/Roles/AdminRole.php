<?php

namespace App\Core\Permissions\Roles;

use App\Core\Permissions\Enums\GuardEnum;
use App\Core\Permissions\Permissions;

final readonly class AdminRole  extends BaseRole
{
    public const NAME = 'admin';

    public function getGuard(): string
    {
        return GuardEnum::Admin_guard();
    }

    public function getPermissions(): array
    {
        return [
            Permissions\Tag\TagPermissionsGroup::class => [
                Permissions\Tag\TagCreatePermission::class,
                Permissions\Tag\TagDeletePermission::class,
                Permissions\Tag\TagReadPermission::class,
                Permissions\Tag\TagUpdatePermission::class,
            ],
        ];
    }
}
