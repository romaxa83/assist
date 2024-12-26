<?php

namespace App\Http\Controllers\Api\Auth;

use App\Core\Permissions\Enums\GuardEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\TokenResource;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use OpenAPI\Operation\ApiGet;


class Controller extends ApiController
{

    public function login(LoginRequest $request): TokenResource
    {
        $login = Auth::attempt($request->only(['email', 'password']));

        throw_unless($login, new AuthenticationException());

        /** @var User $user */
        $user = $request->user();

        $token = $user->createToken('api.user.'.$user->id)
            ->plainTextToken;

        return TokenResource::make(['token' => $token]);
    }
}

