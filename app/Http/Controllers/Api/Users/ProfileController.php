<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Users\ProfileResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends ApiController
{

    public function user(): ProfileResource
    {
        $user = Auth::user();

        return ProfileResource::make($user);
    }
}
