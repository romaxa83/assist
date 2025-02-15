<?php

namespace App\Http\Requests\Api\Notes;

use App\Http\Requests\BaseFormRequest;
use App\Models\Tags\Tag;
use Illuminate\Validation\Rule;

class NotePublicRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->searchRule(),
            $this->paginationRule(),
            [
                'tags' => ['nullable', 'array'],
                'tags.*' => ['required', 'int', Rule::exists(Tag::TABLE, 'id')],
            ]
        );
    }
}
