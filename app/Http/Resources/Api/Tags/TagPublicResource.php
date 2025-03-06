<?php

namespace App\Http\Resources\Api\Tags;

use App\Http\Resources\Api\BaseResource;
use App\Models\Tags\Tag;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyInteger;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Tag
 */

#[BaseScheme(
    resource: TagPublicResource::class,
    properties: [
        new PropertyId(),
        new PropertyString(
            property: 'name',
            example: 'database'
        ),
        new PropertyString(
            property: 'slug',
            example: 'database'
        ),
        new PropertyString(
            property: 'color',
            example: '#d98b84'
        ),
        new PropertyInteger(
            property: 'attached',
            example: 4,
            description: "How many notes are attached to this tag"
        ),
    ]
)]
class TagPublicResource extends BaseResource
{
    public function toArray($request)
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->color,
            'attached' => auth_user()
                ? $this->private_attached
                : $this->public_attached,
        ];
    }
}
