<?php

namespace App\Core\Permissions\Enums;

use App\Core\Enums\Traits\InvokableCases;

/**
 * @method static Admin_guard()
 * @method static User_guard()
 */

enum GuardEnum: string
{
    use InvokableCases;
    case Admin_guard = "admin_guard";
    case User_guard = "user_guard";
}

