<?php

namespace App\Http\Resources\Api\Notes;

use App\Http\Resources\Api\BaseResource;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Notes\Note;
use Illuminate\Support\Facades\Auth;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyInteger;
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
            property: 'status',
            example: 'draft'
        ),
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
        new PropertyInteger(
            property: 'weight',
            example: 8
        ),
        new PropertyString(
            property: 'created_at',
            example: '2025-02-02 11:49'
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
            'status' => $this->status->value,
            'title' => $this->title,
            'slug' => $this->slug,
            'text' => $this->text,
            'weight' => $this->weight,
            'created_at' => date_to_front($this->created_at),
            'tags' => TagResource::collection($this->tags),
            'meta' => $this->getMeta(Auth::guard('sanctum')->user())
        ];
    }
}

