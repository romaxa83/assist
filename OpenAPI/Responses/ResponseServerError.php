<?php

namespace OpenAPI\Responses;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenAPI\Properties\Fields\PropertyBool;
use OpenAPI\Properties\PropertyMessage;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS)]
class ResponseServerError extends OA\Response
{
    public function __construct()
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyBool(property:'success', example:false),
                new PropertyMessage('Server Error')
            ]
        );
        parent::__construct(
            response: Response::HTTP_INTERNAL_SERVER_ERROR,
            description: 'Unauthenticated',
            content: $content
        );
    }
}
