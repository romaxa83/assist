<?php
namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyArray extends OA\Property
{
    public function __construct(
        string $property,
        OA\Schema $items,
        string $description = null,
        bool $nullable = false
    ) {

        if ($nullable) {
            return parent::__construct(
                property: $property,
                description: $description,
                nullable: true,
                anyOf: [
                    new OA\Schema(
                        type: 'array',
                        items: $items
                    ),
                    new OA\Schema(
                        type: 'null',
                    ),
                ]
            );
        }

        parent::__construct(
            property: $property,
            description: $description,
            type: 'array',
            items: $items,
            nullable: false
        );
    }
}
