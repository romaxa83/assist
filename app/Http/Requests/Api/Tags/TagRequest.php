<?php

namespace App\Http\Requests\Api\Tags;

use App\Http\Requests\BaseFormRequest;
use App\Models\Tags\Tag;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: TagRequest::class,
    required: ['name'],
    properties: [
        new PropertyString(
            property: 'name',
            example: 'php'
        ),
        new PropertyString(
            property: 'color',
            example: '#f5baa4',
            nullable: true
        ),
    ]
)]
class TagRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = Route::getCurrentRoute()->parameter('id');

        $rules = [
            'name' => ['required', 'string', Rule::unique(Tag::TABLE, 'name')],
            'color' => ['nullable', 'string'],
        ];

        if($id){
            $rules['name'] = [
                'required',
                'string',
                Rule::unique(Tag::TABLE, 'name')->ignore($id)
            ];
        }

        return $rules;
    }
}



