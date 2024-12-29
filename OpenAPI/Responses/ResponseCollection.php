<?php

namespace OpenAPI\Responses;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ResponseCollection extends OA\Response
{
    public function __construct(
        string $resource,
        int $response = 200,
        string $description = 'Success',
    ) {
        $className = (new \ReflectionClass($resource))->getShortName();
        $ref = "#/components/schemas/{$className}";

//        $data = new OA\Property(
////            property: 'data',
//            type: 'array',
//            items: new OA\Items(
//                $ref
//            ),
//        );
//
//        $content = new OA\JsonContent(
//            properties: [
//                $data
//            ],
//        );

        $content = new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: $ref,
            )
        );

        parent::__construct(
            response: $response,
            description: $description,
            content: $content,
        );
    }
}
