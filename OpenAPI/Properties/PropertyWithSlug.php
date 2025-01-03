<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;
use OpenAPI\Properties\Fields\PropertySlug;

class PropertyWithSlug extends OA\Property
{
    public function __construct(string $property)
    {
        parent::__construct(
            property: $property,
            properties: [
                new PropertySlug(),
            ],
            nullable: false,
            additionalProperties: true
        );
    }
}