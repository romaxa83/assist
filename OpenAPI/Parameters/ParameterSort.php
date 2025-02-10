<?php

namespace OpenAPI\Parameters;

use App\Core\Contracts\Model\Sortable;
use App\Enums\OrderType;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterSort extends OA\Parameter
{
    public function __construct(
        array $columns = [],
        ?string $modelClass = null,
    )
    {
        $description = 'Sort by columns';

        $enum = [];
        if($modelClass){
            /** @var $model Sortable */
            $model = resolve($modelClass);
            $enum = $model::getSortEnums();
            $description .= ' (default - ' .$model::getDefaultSortEnum(). ')';
        }

        foreach ($columns as $column) {
            $enum[] = "{$column}-".OrderType::ASC();
            $enum[] = "{$column}-".OrderType::DESC();
        }

        parent::__construct(
            name: 'sort[]',
            description: $description,
            in: 'query',
            required: false,
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    type: 'enum',
                    enum: $enum
                )
            )
        );
    }
}