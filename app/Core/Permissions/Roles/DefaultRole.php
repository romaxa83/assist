<?php

namespace App\Core\Permissions\Roles;

interface DefaultRole
{
    public function getName(): string;

    public function getGuard(): string;

    public function getPermissions(): array;
}

