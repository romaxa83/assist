<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:191'],
            'password' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => mb_convert_case($this->input('email'), MB_CASE_LOWER),
            ]);
        }
    }
}


