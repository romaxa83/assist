<?php

namespace OpenAPI\Responses;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenAPI\Properties\PropertyString;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS)]
class ResponseSuccessMessage extends OA\Response
{
    public function __construct(string $msg = 'Some message')
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyString(property: 'msg', example: $msg),
            ]
        );
        parent::__construct(
            response: Response::HTTP_OK,
            description: 'Success response with message',
            content: $content
        );
    }
}
