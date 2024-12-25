<?php

namespace App\Core\Permissions\Dto;

class PermissionDto
{
    public string $name;
    public string $guard;


    public static function byArgs(array $data): self
    {
        $self = new self();

        $self->name = $data['name'];
        $self->guard = $data['guard'];

        return $self;
    }
}
