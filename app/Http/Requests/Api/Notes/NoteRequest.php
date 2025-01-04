<?php

namespace App\Http\Requests\Api\Notes;

use App\Http\Requests\BaseFormRequest;
use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use OpenAPI\Properties;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: NoteRequest::class,
    required: ['title'],
    properties: [
        new Properties\PropertyString(
            property: 'title',
            example: 'some title'
        ),
        new Properties\PropertyString(
            property: 'text',
            example: 'some text',
            nullable: true
        ),
        new Properties\PropertyIntArray(
            property: 'tags'
        ),
    ]
)]
class NoteRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = Route::getCurrentRoute()->parameter('id');

        $rules = [
            'title' => ['required', 'string', Rule::unique(Note::TABLE, 'title')],
            'text' => ['nullable', 'string'],
            'tags' => ['nullable' ,'array'],
            'tags.*' => ['required', 'int',
                Rule::exists(Tag::TABLE, 'id')
            ],
        ];

        if($id){
            $rules['title'] = [
                'required',
                'string',
                Rule::unique(Note::TABLE, 'title')->ignore($id)
            ];
        }

        return $rules;
    }
}



