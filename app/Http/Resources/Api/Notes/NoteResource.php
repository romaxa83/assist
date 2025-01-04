<?php

namespace App\Http\Resources\Api\Notes;

use App\Http\Resources\Api\BaseResource;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Notes\Note;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyResourceCollection;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Note
 */

#[BaseScheme(
    resource: NoteResource::class,
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
        new PropertyString(
            property: 'text',
            example: 'some text'
        ),
        new PropertyResourceCollection(
            property: 'tags',
            resource: TagResource::class,
        )
    ]
)]
class NoteResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'text' => $this->text,
            'tags' => TagResource::collection($this->tags),
        ];
    }
}

