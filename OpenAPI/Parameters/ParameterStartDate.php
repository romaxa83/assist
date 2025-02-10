<?php

namespace OpenAPI\Parameters;

use App\Enums\DateFormat;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class ParameterStartDate extends ParameterString
{
    public function __construct(
        ?string $description = null,
        ?bool $required = false,
    ) {
        $description = $description ?? "Filter by start_date, format - " . DateFormat::FRONT_FILTER();

        parent::__construct(
            parameter: 'start_date',
            description: $description,
            required: $required,
        );
    }
}