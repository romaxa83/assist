<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyMessage extends OA\Property
{
    public function __construct(string $msg = 'Some message')
    {
        parent::__construct(
            property: 'msg',
            type: 'string',
            example: $msg
        );
    }
}
