<?php

namespace App\Core\Permissions\Roles;

use App\Core\Permissions\Enums\GuardEnum;
use App\Core\Permissions\Permissions;

final readonly class UserRole  extends BaseRole
{
    public const NAME = 'user';

    public function getGuard(): string
    {
        return GuardEnum::Admin_guard();
    }

    public function getPermissions(): array
    {
        return [
            Permissions\Tag\TagPermissionsGroup::class => [
                Permissions\Tag\TagReadPermission::class,
            ],
        ];
    }
}
