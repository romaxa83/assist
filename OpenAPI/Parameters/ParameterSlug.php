<?php

namespace OpenAPI\Parameters;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterSlug extends OA\Parameter
{
    public function __construct(
        string $description = 'Slug model'
    )
    {
        parent::__construct(
            name: '{slug}',
            in: 'path',
            required: true,
            description: $description,
            schema:  new OA\Schema(
                type: 'string',
                default: 'slug'
            ),
        );
    }
}
