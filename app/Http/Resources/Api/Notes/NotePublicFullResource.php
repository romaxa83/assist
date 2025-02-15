<?php

namespace App\Http\Resources\Api\Notes;

use App\Http\Resources\Api\BaseResource;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Notes\Note;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyInteger;
use OpenAPI\Properties\PropertyObjArray;
use OpenAPI\Properties\PropertyResourceCollection;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Note
 */

#[BaseScheme(
    resource: NotePublicFullResource::class,
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
        new PropertyObjArray(
            property: 'links',
            items: [
                new PropertyString(
                    property: 'title',
                    example: 'Original text',
                ),
                new PropertyString(
                    property: 'link',
                    example: 'https://example.com'
                ),
            ]
        ),
        new PropertyObjArray(
            property: 'anchors',
            items: [
                new PropertyString(
                    property: 'tag',
                    example: 'h2',
                ),
                new PropertyString(
                    property: 'id',
                    example: 'nav-1'
                ),
                new PropertyString(
                    property: 'content',
                    example: 'nav 1'
                ),
            ]
        ),
        new PropertyObjArray(
            property: 'text_blocks',
            items: [
                new PropertyString(
                    property: 'type',
                    example: 'text',
                ),
                new PropertyString(
                    property: 'language',
                    example: "text/html"
                ),
                new PropertyString(
                    property: 'content',
                    example: '<p>Did we mention that you have full control over the rendering</p>'
                ),
            ]
        ),
    ]
)]
class NotePublicFullResource extends BaseResource
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
            'anchors' => $this->anchors,
            'links' => $this->links,
            'text_blocks' => $this->text_blocks,
        ];
    }
}
