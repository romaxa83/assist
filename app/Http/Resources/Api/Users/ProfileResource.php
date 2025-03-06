<?php

namespace App\Http\Resources\Api\Users;

use App\Http\Resources\Api\BaseResource;
use App\Models\Users\User;
use OpenAPI\Properties\Fields\PropertyEmail;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin User
 */
#[BaseScheme(
    resource: ProfileResource::class,
    properties: [
        new PropertyId(),
        new PropertyString(
            property: 'name',
            example: 'John Doe'
        ),
        new PropertyEmail(),
    ]
)]
class ProfileResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
