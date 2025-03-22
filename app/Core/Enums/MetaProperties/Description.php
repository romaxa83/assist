<?php

declare(strict_types=1);

namespace App\Core\Enums\MetaProperties;

use App\Core\Enums\Meta\MetaProperty;
use Attribute;

#[Attribute]
class Description extends MetaProperty {

    public static function method(): string
    {
        return 'note';
    }
}