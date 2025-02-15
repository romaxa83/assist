<?php

namespace App\Http\Resources\Api\Notes;

use App\Http\Resources\Api\BaseResource;
use App\Models\Notes\Note;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\Property;
use OpenAPI\Properties\Fields\PropertyId;
use OpenAPI\Properties\PropertyInteger;
use OpenAPI\Properties\PropertyObjArray;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Note
 */

#[BaseScheme(
    resource: NotePrivateSimpleResource::class,
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
        new PropertyInteger(
            property: 'weight',
            example: 8
        ),
        new PropertyString(
            property: 'created_at',
            example: '2025-02-02'
        ),

        new Property(
            description: "Данные по модели, которые помогают обрабатывать ее",
            property: 'meta',
            type: 'object',
            properties: [
                new PropertyObjArray(
                    description: "Действия которые можно сделать с моделью для конкретного пользователя",
                    property: 'actions',
                    items: []
                )
            ]
        )
    ]
)]
class NotePrivateSimpleResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'title' => $this->title,
            'slug' => $this->slug,
            'weight' => $this->weight,
            'created_at' => date_to_front($this->created_at),
            'meta' => $this->getMetaForSimplePrivate(Auth::guard('sanctum')->user())
        ];
    }
}


