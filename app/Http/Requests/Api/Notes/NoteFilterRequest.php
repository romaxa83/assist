<?php

namespace App\Http\Requests\Api\Notes;

use App\Http\Requests\BaseFormRequest;

class NoteFilterRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return $this->searchRule();
    }
}



