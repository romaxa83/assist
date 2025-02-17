<?php

namespace App\Http\Resources\Api\Notes;

use App\Http\Resources\Api\BaseResource;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Notes\Note;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyInteger;
use OpenAPI\Properties\PropertyResourceCollection;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Note
 */

#[BaseScheme(
    resource: NotePublicSimpleResource::class,
    properties: [
        new PropertyId(),
        new PropertyString(
            property: 'title',
            example: 'database'
        ),
        new PropertyString(
            property: 'slug',
            example: 'database'
        ),
        new PropertyInteger(
            property: 'weight',
            example: 8
        ),
        new PropertyString(
            property: 'created_at',
            example: '2025-02-02'
        ),
        new PropertyResourceCollection(
            property: 'tags',
            resource: TagResource::class,
        ),
    ]
)]
class NotePublicSimpleResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'weight' => $this->weight,
            'created_at' => date_to_front($this->created_at),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}


