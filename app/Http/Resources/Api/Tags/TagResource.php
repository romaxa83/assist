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
    resource: TagResource::class,
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
            property: 'public_attached',
            example: 4,
            description: "How many notes are attached to this tag in public notes"
        ),
        new PropertyInteger(
            property: 'private_attached',
            example: 8,description: "How many notes are attached to this tag in private notes and public notes"
        ),
    ]
)]
class TagResource extends BaseResource
{
    public function toArray($request)
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->color,
            'public_attached' => $this->public_attached,
            'private_attached' => $this->private_attached,
        ];
    }
}
