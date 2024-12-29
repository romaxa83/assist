<?php

namespace OpenAPI\Parameters\Headers;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class Authorization extends OA\Parameter
{
    public function __construct() {

        parent::__construct(
            parameter: 'Authorization',
            name: 'Authorization',
            in: 'header',
            required: true,
            schema:  new OA\Schema(
                type: 'string',
                default: "Bearer <authorization_token>"
            ),
        );
    }
}
