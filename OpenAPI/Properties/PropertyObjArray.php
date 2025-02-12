<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyObjArray extends OA\Property
{
    public function __construct(
        string $property,
        ?string $description = null,
        bool $nullable = false,
        array $items = [],
    ) {
        parent::__construct(
            property: $property,
            description: $description,
            type: 'array',
            items: new OA\Items(properties: $items),
            nullable: $nullable
        );
    }
}