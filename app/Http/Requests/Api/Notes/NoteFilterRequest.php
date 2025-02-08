<?php

namespace App\Http\Requests\Api\Notes;

use App\Enums\Notes\NoteStatus;
use App\Http\Requests\BaseFormRequest;
use App\Models\Tags\Tag;
use Illuminate\Validation\Rule;

class NoteFilterRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->idRule(),
            $this->searchRule(),
            $this->paginationRule(),
            [
                'status' => ['nullable', 'string', NoteStatus::ruleIn()],
                'tags' => ['nullable', 'array'],
                'tags.*' => ['required', 'int', Rule::exists(Tag::TABLE, 'id')],
            ]
        );
    }
}



