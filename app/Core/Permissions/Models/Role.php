<?php

namespace App\Core\Permissions\Models;

use App\Core\Permissions\Enums\GuardEnum;
use App\Core\Permissions\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int id
 * @property string guard_name
 * @property string name
 * @property string created_at
 * @property string updated_at
 *
 * @property Collection|Permission[] permissions
 *
 * @method static Builder|static admin()
 *
 */
class Role extends \Spatie\Permission\Models\Role
{
    protected $table = self::TABLE;
    public const TABLE = 'roles';

    public static function defaultRoles(): array
    {
        return [
            Roles\AdminRole::class,
            Roles\UserRole::class,
        ];
    }

    public function isRole(string $roleName): bool
    {
        return $this->name === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->name === Roles\AdminRole::NAME
            && $this->guard_name === GuardEnum::Admin_guard();
    }

    public function scopeAdmin($query)
    {
        return $query
            ->where('name', Roles\AdminRole::NAME)
            ->where('guard_name', GuardEnum::Admin_guard())
            ;
    }
}


