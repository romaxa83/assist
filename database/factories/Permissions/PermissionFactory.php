<?php

namespace Database\Factories\Permissions;

use App\Core\Permissions\Enums\GuardEnum;
use App\Core\Permissions\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'guard_name' => GuardEnum::Admin_guard(),
        ];
    }
}
