<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use OpenAPI\Operation\ApiGet;

#[ApiGet(
    path: '/api/tags',
    tags: ['Auth'],
    description: 'Get tags',
    auth: true
)]
class AuthController extends ApiController
{

//    #[ApiGet(
//        path: '/api/tags',
//        tags: ['Auth'],
//        description: 'Get tags',
//        auth: true
//    )]
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->successJsonMessage('Tags retrieved successfully');
    }
}

