<?php

namespace App\Http\Resources\Api\Users;

use App\Http\Resources\Api\BaseResource;
use App\Models\Users\User;
use Illuminate\Http\Request;

/**
 * @mixin User
 */
class ProfileResource extends BaseResource
{
    protected function transformResource(Request $request): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
