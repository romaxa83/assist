<?php

namespace App\Http\Resources\Api\Auth;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

class TokenResource extends BaseResource
{
    public function transformResource(Request $request): array
    {
        return [
            'token' => $this['token'],
        ];
    }
}
