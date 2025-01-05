<?php

namespace OpenAPI\Parameters;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterPage extends OA\Parameter
{
    public function __construct() {

        parent::__construct(
            name: 'page',
            in: 'query',
            required: false,
            description: 'Page number',
            example: 1,
        );
    }
}
