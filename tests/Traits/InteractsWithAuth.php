<?php

namespace Tests\Traits;

use App\Core\Permissions\Models\Role;
use App\Models\Users\User;
use Illuminate\Auth\Authenticatable;
use Tests\Builders\Users\UserBuilder;

trait InteractsWithAuth
{
    public function loginAsAdmin(
        Authenticatable|User|null $user = null,
    ): User
    {
        if (!$user) {
            if(property_exists($this, 'userBuilder')){
                $user = $this->userBuilder->create();
            } else {
                $builder = resolve(UserBuilder::class);
                $user = $builder->create();
            }
        }

        /** @var $role Role */
        $role = Role::admin()->first();

        $user->assignRole($role);

        return $this->acting($user, $role->guard_name);
    }

    private function acting(User $user, $guard = 'sanctum'): User
    {
        $token = $user->createToken('api.user.'.$user->id);

        $user->withAccessToken($token->accessToken);

        app('auth')->guard($guard)->setUser($user);
        app('auth')->shouldUse($guard);

        return $user;
    }

}


