<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyInterval extends OA\Property
{
    public function __construct(
        string $property,
        bool $nullable = true
    ) {
        $items = [
            new PropertyInteger(
                property: 'years',
                example: 2
            ),
            new PropertyInteger(
                property: 'months',
                example: 10
            ),
            new PropertyInteger(
                property: 'weeks',
                example: 3
            ),
            new PropertyInteger(
                property: 'days',
                example: 3
            ),
            new PropertyInteger(
                property: 'hours',
                example: 12
            ),
            new PropertyInteger(
                property: 'minutes',
                example: 50
            ),
            new PropertyInteger(
                property: 'seconds',
                example: 10
            ),
            new PropertyInteger(
                property: 'microseconds',
                example: 10
            ),
        ];

        if ($nullable) {
            return parent::__construct(
                property: $property,
                nullable: true,
                anyOf: [
                    new OA\Schema(
                        properties: $items,
                        type: 'object',
                    ),
                    new OA\Schema(
                        type: 'null',
                    ),
                ]
            );
        }

        return parent::__construct(
            property: $property,
            properties: $items,
            type: 'object',
        );
    }
}