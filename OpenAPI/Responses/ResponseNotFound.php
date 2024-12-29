<?php

namespace OpenAPI\Responses;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenAPI\Properties\PropertyMessage;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS)]
class ResponseNotFound extends OA\Response
{
    public function __construct()
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyMessage(__('Not Found'))
            ]
        );
        parent::__construct(
            response: Response::HTTP_NOT_FOUND,
            description: 'The requested data was not found.',
            content: $content
        );
    }
}
