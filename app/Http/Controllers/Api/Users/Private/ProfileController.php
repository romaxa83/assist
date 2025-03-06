<?php

namespace App\Http\Controllers\Api\Users\Private;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Users\ProfileResource;
use Illuminate\Support\Facades\Auth;
use OpenAPI\Operation;
use OpenAPI\Parameters;
use OpenAPI\Responses;

class ProfileController extends ApiController
{
    #[Operation\ApiGet(
        path: '/api/private/profile',
        tags: ['Profile private'],
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
