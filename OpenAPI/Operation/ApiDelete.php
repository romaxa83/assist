<?php

namespace OpenAPI\Operation;

use OpenApi\Annotations\Delete;
use OpenAPI\Operation\Common\ApiOperation;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ApiDelete extends Delete
{
    use ApiOperation;
}

