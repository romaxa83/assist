<?php

namespace OpenAPI\Parameters\Headers;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ContentType extends OA\Parameter
{
    public function __construct() {

        parent::__construct(
            parameter: 'Content-type',
            name: 'Content-type',
            in: 'header',
            required: true,
            schema:  new OA\Schema(
                type: 'string',
                default: "application/json"
            ),
        );
    }
}
