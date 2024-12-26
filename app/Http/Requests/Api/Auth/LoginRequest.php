<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\BaseFormRequest;
use ArondeParon\RequestSanitizer\Sanitizers\Lowercase;
use ArondeParon\RequestSanitizer\Traits\SanitizesInputs;

class LoginRequest extends BaseFormRequest
{
    use SanitizesInputs;
    protected $sanitizers = [
        'email' => [
            Lowercase::class,
        ],
    ];

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:191'],
            'password' => ['required', 'string'],
        ];
    }
}


