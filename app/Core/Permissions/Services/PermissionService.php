<?php

namespace App\Core\Permissions\Services;

use App\Core\Permissions\Dto\PermissionDto;
use App\Core\Permissions\Models\Permission;

final class PermissionService
{
    public function __construct()
    {}

    public function create(
        PermissionDto $dto,
    ): Permission
    {
        $model = $this->fill(new Permission(), $dto);

        $model->save();

        return $model;
    }

    public function update(
        Permission $model,
        PermissionDto $dto,
    ): Permission
    {
        $model = $this->fill($model, $dto);

        $model->save();

        return $model;
    }

    private function fill(Permission $model, PermissionDto $dto): Permission
    {
        $model->name = $dto->name;
        $model->guard_name = $dto->guard;

        return $model;
    }
}
