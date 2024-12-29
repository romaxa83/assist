<?php

namespace OpenAPI\Parameters;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterSearch extends OA\Parameter
{
    public function __construct() {

        parent::__construct(
            name: 'search',
            in: 'query',
            required: false,
        );
    }
}
