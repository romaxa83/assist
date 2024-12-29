<?php

namespace OpenAPI\Operation;

use OpenApi\Annotations\Get;
use OpenAPI\Operation\Common\ApiOperation;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ApiGet extends Get
{
    use ApiOperation;
}
