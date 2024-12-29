<?php

namespace OpenAPI\Properties\Fields;

use OpenApi\Attributes as OA;

class PropertyId extends OA\Property
{
    public function __construct(
        string $description = 'Model id'
    )
    {
        parent::__construct(
            property: 'id',
            description: $description,
            type: 'integer',
            example: 22,
            nullable: false,
        );
    }
}

