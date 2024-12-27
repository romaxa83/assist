<?php

namespace Tests\Builders\Users;

use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;
use Tests\Builders\BaseBuilder;

class UserBuilder extends BaseBuilder
{
    function modelClass(): string
    {
        return User::class;
    }

    public function password(string $value): self
    {
        $this->data['password'] = Hash::make($value);
        return $this;
    }

    public function email(string $value): self
    {
        $this->data['email'] = $value;
        return $this;
    }

    public function name(string $value): self
    {
        $this->data['name'] = $value;
        return $this;
    }
}

