<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\BaseFormRequest;
use ArondeParon\RequestSanitizer\Sanitizers\Lowercase;
use OpenAPI\Properties\PropertyString;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: LoginRequest::class,
    properties: [
        new PropertyString(
            property: 'email',
            example: 'example@example.com'
        ),
        new PropertyString(
            property: 'password',
            example: 'Pas$word1'
        ),
    ]
)]
class LoginRequest extends BaseFormRequest
{
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


