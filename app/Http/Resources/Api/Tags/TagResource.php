<?php

namespace App\Http\Resources\Api\Tags;

use App\Http\Resources\Api\BaseResource;
use App\Models\Tags\Tag;
use Illuminate\Http\Request;
use OpenAPI\Properties\Fields\PropertyBool;
use OpenAPI\Properties\PropertyObject;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

/**
 * @mixin Tag
 */

#[BaseScheme(
    resource: TagResource::class,
    properties: [
        new PropertyBool(property: 'success'),
        new PropertyObject(
            property: 'data',
            properties: [
                'token' => new PropertyString(
                    property: 'token',
                    example: '46|R9Nb3rAbdVnJT1EEI0fr56YzwP29QKtovoQ2tM2j47235739'
                ),
            ]
        ),
    ]
)]
class TagResource extends BaseResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->color
        ];

        dd($this->additional);

        if($this->additional['is_single'])
            return ['success' => true, 'data' => $data];

        return $data;
    }
}
