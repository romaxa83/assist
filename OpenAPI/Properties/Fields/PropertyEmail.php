<?php

namespace OpenAPI\Properties\Fields;

use OpenApi\Attributes as OA;

class PropertyEmail extends OA\Property
{
    public function __construct(
        string $description = 'Email'
    )
    {
        parent::__construct(
            property: 'email',
            description: $description,
            type: 'string',
            example: 'example@gmail.com',
            nullable: false,
        );
    }
}

