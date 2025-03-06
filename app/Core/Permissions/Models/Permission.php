<?php

namespace App\Core\Permissions\Models;

use Carbon\Carbon;
use Database\Factories\Permissions\PermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int id
 * @property string name
 * @property string guard_name
 * @property string group
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 *
 * @method static PermissionFactory factory(...$parameters)
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    protected $table = self::TABLE;
    public const TABLE = 'permissions';

    protected static function newFactory(): PermissionFactory
    {
        return PermissionFactory::new();
    }
}

