<?php

namespace App\Http\Requests\Api\Notes;

use App\Http\Requests\BaseFormRequest;
use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: NoteRequest::class,
    required: ['title'],
    properties: [
        new PropertyString(
            property: 'title',
            example: 'some title'
        ),
        new PropertyString(
            property: 'text',
            example: 'some text',
            nullable: true
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



