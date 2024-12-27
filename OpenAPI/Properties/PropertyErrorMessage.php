<?php

namespace OpenAPI\Properties;

use OpenApi\Attributes as OA;

class PropertyErrorMessage extends OA\Property
{
    public function __construct(string $msg = 'Some error message')
    {
        parent::__construct(
            property: 'message',
            type: 'string',
            example: $msg
        );
    }
}
