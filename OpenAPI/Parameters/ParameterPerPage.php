<?php

namespace OpenAPI\Parameters;

use App\Models\BaseModel;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterPerPage extends OA\Parameter
{
    public function __construct() {

        parent::__construct(
            name: 'per_page',
            in: 'query',
            required: false,
            description: 'Number of records per page',
            example: BaseModel::DEFAULT_PER_PAGE,
        );
    }
}
