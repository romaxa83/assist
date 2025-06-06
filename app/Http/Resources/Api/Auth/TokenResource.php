<?php

namespace App\Http\Resources\Api\Auth;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;
use OpenAPI\Properties\PropertyBool;
use OpenAPI\Properties\PropertyObject;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: TokenResource::class,
    properties: [
        new PropertyString(
            property: 'token',
            example: '46|R9Nb3rAbdVnJT1EEI0fr56YzwP29QKtovoQ2tM2j47235739'
        ),
    ]
)]
class TokenResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->resource,
        ];
    }
}
