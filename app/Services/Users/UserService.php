<?php

namespace App\Services\Users;

use App\Core\Permissions\Models\Role;
use App\Dto\Users\UserDto;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;

final class UserService
{
    public function __construct()
    {}

    public function create(
        UserDto $dto,
    ): User
    {
        return make_transaction(function() use ($dto) {
            $model = $this->fill(new User(), $dto);
            $model = $this->setPassword($model, $dto->password);

            $model->save();

            if($dto->role instanceof Role){
//                dd($model->getGuarded());

                $model->guard([$dto->role->guard_name])
                    ->assignRole($dto->role->id);
            } else {
                $model->assignRole($dto->role->id);
            }

            return $model;
        });
    }

    public function setPassword(
        User $model,
        string $password,
        bool $save = false
    ): User
    {
        $model->password = Hash::make($password);

        if($save){
            $model->save();
        }

        return $model;
    }

    private function fill(User $model, UserDto $dto): User
    {
        $model->name = $dto->name;
        $model->email = $dto->email;

        return $model;
    }
}
