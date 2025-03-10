<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyString extends OA\Property
{
    public function __construct(
        string $property,
        bool $nullable = false,
        string $example = 'Some string',
        ?string $description = null,
        ?string $format = null,
    ) {
        if ($nullable) {
            return parent::__construct(
                property: $property,
                description: $description,
                format: $format,
                example: $example,
                nullable: true,
                anyOf: [
                    new OA\Schema(
                        type: 'string',
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
            type: 'string',
            format: $format,
            example: $example,
            nullable: false,
        );
    }
}
