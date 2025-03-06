<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin Model
 */
abstract class BaseAuthenticatableModel extends User
{
    use HasApiTokens;
    use HasRoles;

    public const MIN_LENGTH_PASSWORD = 8;
}

