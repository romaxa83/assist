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
            property: 'weight',
            example: 4
        )
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
            'weight' => $this->weight
        ];
    }
}
