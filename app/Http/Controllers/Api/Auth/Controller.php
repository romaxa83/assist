<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\TokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use OpenAPI\Operation\ApiPost;
use OpenAPI\Request\RequestJson;
use OpenAPI\Responses;

class Controller extends ApiController
{
    #[ApiPost(
        path: '/api/login',
        tags: ['Auth'],
        description: 'Login user',
    )]
    #[RequestJson(LoginRequest::class)]
    #[Responses\ResponseJsonSuccess(TokenResource::class)]
    #[Responses\ResponseUnauthorized]
    #[Responses\ResponseServerError]
    public function login(LoginRequest $request): TokenResource
    {
        $login = Auth::attempt($request->only(['email', 'password']));

        throw_unless($login, new AuthenticationException());

        $user = $request->user();

        $token = $user->createToken('api.user.'.$user->id)
            ->plainTextToken;

        return TokenResource::make(['token' => $token]);
    }

    #[ApiPost(
        path: '/api/logout',
        tags: ['Auth'],
        description: 'Logout user',
        auth: true
    )]
    #[Responses\ResponseSuccessMessage('Logout')]
    #[Responses\ResponseServerError]
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return self::successJsonMessage('Logout');
    }
}

