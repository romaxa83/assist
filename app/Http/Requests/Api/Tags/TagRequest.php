<?php

namespace App\Http\Requests\Api\Tags;

use App\Http\Requests\BaseFormRequest;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: TagRequest::class,
    properties: [
        new PropertyString(
            property: 'name',
            example: 'php'
        ),
        new PropertyString(
            property: 'color',
            example: '#f5baa4'
        ),
    ]
)]
class TagRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'color' => ['nullable', 'string'],
        ];
    }
}



