<?php

namespace OpenAPI\Responses;

use OpenApi\Attributes as OA;
use OpenAPI\Properties\PropertyString;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ResponseNewItem extends OA\Response
{
    public function __construct()
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyString('slug'),
            ]
        );
        parent::__construct(
            response: 201,
            description: 'Successfully created',
            content: $content
        );
    }
}
