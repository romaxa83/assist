<?php

namespace App\Http\Requests\Api\Tags;

use App\Http\Requests\BaseFormRequest;

class TagFilterRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return $this->searchRule();
    }
}



