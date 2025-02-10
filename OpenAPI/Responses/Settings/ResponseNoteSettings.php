<?php

namespace OpenAPI\Responses\Settings;

use OpenApi\Attributes as OA;
use OpenAPI\Properties\PropertyString;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ResponseNoteSettings extends OA\Response
{
    public function __construct()
    {
        $statuses = new OA\Property(
            property: 'statuses',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new PropertyString(
                        property: 'value',
                        example: 'draft',
                    ),
                    new PropertyString(
                        property: 'label',
                        example: 'Draft'
                    ),
                ]
            )
        );

        $content = new OA\JsonContent(
            properties: [
                $statuses,
            ],
        );

        parent::__construct(
            response: 200,
            description: 'Success',
            content: $content
        );
    }
}

