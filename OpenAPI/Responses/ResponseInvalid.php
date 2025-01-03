<?php

namespace OpenAPI\Responses;

use OpenApi\Attributes as OA;
use OpenAPI\Properties\PropertyErrorMessage;
use OpenAPI\Properties\PropertyObject;
use OpenAPI\Properties\PropertyStringArray;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ResponseInvalid extends OA\Response
{
    public function __construct()
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyErrorMessage(),
                new PropertyObject(
                    property: 'errors',
                    properties: [
                        new PropertyStringArray(
                            property: 'field',
                            example: ['validation error by this field']
                        )
                    ]
                )
            ]
        );
        parent::__construct(
            response: 422,
            description: 'Invalid data',
            content: $content
        );
    }
}
