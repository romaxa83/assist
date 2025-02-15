<?php

namespace App\Http\Resources\Api\Notes;

use App\Http\Resources\Api\BaseResource;
use App\Models\Notes\Note;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Note
 */

#[BaseScheme(
    resource: NotePrivateShortResource::class,
    properties: [
        new PropertyId(),
        new PropertyString(
            property: 'title',
            example: 'database'
        ),
    ]
)]
class NotePrivateShortResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}