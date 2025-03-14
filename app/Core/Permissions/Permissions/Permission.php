<?php

namespace App\Core\Permissions\Permissions;

use Illuminate\Contracts\Support\Arrayable;

interface Permission extends Arrayable
{
    public function getKey(): string;

    public function getName(): string;

    public function getPosition(): int;
}
