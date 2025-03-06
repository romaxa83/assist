<?php

namespace App\Core\Permissions\Dto;

class RoleDto
{
    public string $name;
    public string $guard;
    public array $permissionsIds = [];


    public static function byArgs(array $data): self
    {
        $self = new self();

        $self->name = $data['name'];
        $self->guard = $data['guard'];

        $self->permissionsIds = $data['permission_ids'] ?? [];

        return $self;
    }
}
