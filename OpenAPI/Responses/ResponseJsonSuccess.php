<?php

namespace OpenAPI\Responses;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ResponseJsonSuccess extends OA\Response
{
    public function __construct(
        string $resource,
        int $response = 200,
        string $description = 'Success',
    ) {
        $className = (new \ReflectionClass($resource))->getShortName();
        $ref = "#/components/schemas/{$className}";

        $content = new OA\JsonContent(ref: $ref);

        parent::__construct(
            response: $response,
            description: $description,
            content: $content,
        );
    }
}
