<?php

namespace OpenAPI\Responses;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenAPI\Properties\Fields\PropertyBool;
use OpenAPI\Properties\PropertyMessage;
use OpenAPI\Properties\PropertyObject;
use OpenAPI\Properties\PropertyString;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS)]
class ResponseSuccessMessage extends OA\Response
{
    public function __construct(string $msg = 'Some message')
    {
        $content = new OA\JsonContent(
            properties: [
                new PropertyBool(property:'success', example:true),
                new PropertyObject(
                    property: 'data',
                    properties: [
                        new PropertyString(property: 'msg', example: $msg),
                    ]
                ),
            ]
        );
        parent::__construct(
            response: Response::HTTP_OK,
            description: 'Success response with message',
            content: $content
        );
    }
}
