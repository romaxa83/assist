<?php

namespace OpenAPI\Parameters;

use App\Enums\DateFormat;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterEndDate extends ParameterString
{
    public function __construct(
        ?string $description = null,
        ?bool $required = false,
    ) {
        $description = $description ?? "Filter by end_date, format - " . DateFormat::FRONT_FILTER();

        parent::__construct(
            parameter: 'end_date',
            description: $description,
            required: $required,
        );
    }
}