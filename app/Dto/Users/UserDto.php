<?php

namespace App\Dto\Users;

use App\Core\Permissions\Models\Role;

class UserDto
{
    public string $name;
    public string $email;
    public string $password;
    public Role|int $role;


    public static function byArgs(array $data): self
    {
        $self = new self();

        $self->name = $data['name'];
        $self->email = $data['email'];
        $self->password = $data['password'];
        $self->role = $data['role'];

        return $self;
    }
}

