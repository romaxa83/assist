<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyIntArray extends OA\Property
{
    public function __construct(
        string $property,
        bool $nullable = false,
        ?array $example = [1,2],
    ) {
        parent::__construct(
            property: $property,
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: $example,
            nullable: $nullable
        );
    }
}