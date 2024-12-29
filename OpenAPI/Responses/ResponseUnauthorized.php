<?php

namespace OpenAPI\Responses;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenAPI\Properties\PropertyMessage;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS)]
class ResponseUnauthorized extends OA\Response
{
    public function __construct()
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyMessage('Unauthorized')
            ]
        );
        parent::__construct(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'Unauthenticated',
            content: $content
        );
    }
}
