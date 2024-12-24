<?php

namespace OpenAPI\Properties;

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
