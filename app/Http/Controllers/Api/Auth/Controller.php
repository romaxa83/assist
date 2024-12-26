<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\TokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\PersonalAccessToken;


class Controller extends ApiController
{

    public function login(LoginRequest $request): TokenResource
    {
        $login = Auth::attempt($request->only(['email', 'password']));

        throw_unless($login, new AuthenticationException());

        $user = $request->user();

        $token = $user->createToken('api.user.'.$user->id)
            ->plainTextToken;

        return TokenResource::make(['token' => $token]);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return self::successJsonMessage('Logout');
    }
}

