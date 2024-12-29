<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyObject extends OA\Property
{
    public function __construct(
        array $properties,
        ?string $property = null,
        string $description = null,
        bool $nullable = false
    ) {

        parent::__construct(
            type: 'object',
            property: $property,
            properties: $properties,
            description: $description,
            nullable: $nullable
        );
    }
}
