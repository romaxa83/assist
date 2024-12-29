<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Users\ProfileResource;
use Illuminate\Support\Facades\Auth;
use OpenAPI\Operation;
use OpenAPI\Responses;
use OpenAPI\Parameters;

class ProfileController extends ApiController
{
    #[Operation\ApiGet(
        path: '/api/profile',
        tags: ['Profile'],
        description: 'Get auth user',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Responses\ResponseJsonSuccess(ProfileResource::class)]
    #[Responses\ResponseServerError]
    public function user(): ProfileResource
    {
        $user = Auth::user();

        return ProfileResource::make($user);
    }
}
