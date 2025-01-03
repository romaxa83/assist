<?php

namespace OpenAPI\Properties\Fields;

class PropertySlug extends \OpenAPI\Properties\PropertyString
{
    public function __construct()
    {
        return parent::__construct(
            property: 'slug',
            description: 'unique identifier',
        );
    }
}
