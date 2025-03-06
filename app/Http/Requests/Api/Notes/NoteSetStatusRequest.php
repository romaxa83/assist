<?php

namespace App\Http\Requests\Api\Notes;

use App\Http\Requests\BaseFormRequest;
use App\Models\Notes\Note;
use OpenAPI\Properties;
use OpenAPI\Schemas\BaseScheme;
use Illuminate\Support\Facades\Route;

#[BaseScheme(
    resource: NoteSetStatusRequest::class,
    required: ['status'],
    properties: [
        new Properties\PropertyString(
            property: 'status',
            example: 'draft'
        ),
    ]
)]
class NoteSetStatusRequest extends BaseFormRequest
{
    private ?Note $note = null;

    public function rules(): array
    {
        // todo добавить валидацию, что переданный статус для note можно поменять
        return [
            'status' => ['required', 'string'],
        ];
    }

    public function getNote(): Note
    {
        if($this->note){
            return $this->note;
        }

        $id = Route::getCurrentRoute()->parameter('id');
        $this->note = Note::query()->findOrFail($id);

        return $this->note;
    }
}




