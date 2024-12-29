<?php

namespace OpenAPI\Parameters;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterId extends OA\Parameter
{
    public function __construct(
        string $description = 'ID model'
    )
    {
        parent::__construct(
            name: '{id}',
            in: 'path',
            required: true,
            description: $description,
            schema:  new OA\Schema(
                type: 'integer',
                default: 1
            ),
        );
    }
}
