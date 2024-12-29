<?php

namespace OpenAPI\Operation;

use OpenApi\Annotations\Put;
use OpenAPI\Operation\Common\ApiOperation;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ApiPut extends Put
{
    use ApiOperation;
}
