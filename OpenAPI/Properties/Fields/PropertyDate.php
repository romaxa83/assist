<?php

namespace OpenAPI\Properties\Fields;

use OpenAPI\Properties\PropertyString;

class PropertyDate extends PropertyString
{
    public function __construct(
        string $property,
        bool $nullable = false,
        ?string $description = null
    ) {
        $example = '2024-01-01';

        parent::__construct(
            property: $property,
            nullable: $nullable,
            example: $example,
            description: $description,
            format: 'date',
        );
    }
}
