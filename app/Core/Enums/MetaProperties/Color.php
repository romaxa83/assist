<?php

declare(strict_types=1);

namespace App\Core\Enums\MetaProperties;

use App\Core\Enums\Meta\MetaProperty;
use Attribute;

#[Attribute]
class Color extends MetaProperty {

    protected function transform(mixed $value): mixed
    {
        return "text-{$value}-500";
    }
}