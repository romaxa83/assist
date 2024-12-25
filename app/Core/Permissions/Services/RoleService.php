<?php

namespace App\Core\Permissions\Services;

use App\Core\Permissions\Dto\RoleDto;
use App\Core\Permissions\Models\Role;

final class RoleService
{
    public function __construct()
    {}

    public function create(
        RoleDto $dto,
    ): Role
    {
        $model = $this->fill(new Role(), $dto);

        $model->save();

        $model->permissions()->sync($dto->permissionsIds);

        return $model;
    }

    public function update(
        Role $model,
        RoleDto $dto,
    ): Role
    {
        $model = $this->fill($model, $dto);

        $model->save();

        $model->permissions()->sync($dto->permissionsIds);

        return $model;
    }

    private function fill(Role $model, RoleDto $dto): Role
    {
        $model->name = $dto->name;
        $model->guard_name = $dto->guard;

        return $model;
    }
}
