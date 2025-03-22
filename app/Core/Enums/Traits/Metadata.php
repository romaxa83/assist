<?php

declare(strict_types=1);

namespace App\Core\Enums\Traits;

use App\Core\Enums\Meta;
use ValueError;

trait Metadata
{
    /** Try to get the first case with this meta property value. */
    public static function tryFromMeta(Meta\MetaProperty $metaProperty): static|null
    {
        foreach (static::cases() as $case) {
            if (Meta\Reflection::metaValue($metaProperty::class, $case) === $metaProperty->value) {
                return $case;
            }
        }

        return null;
    }

    /** Get the first case with this meta property value. */
    public static function fromMeta(Meta\MetaProperty $metaProperty): static
    {
        return static::tryFromMeta($metaProperty) ?? throw new ValueError(
            'Enum ' . static::class . ' does not have a case with a meta property "' .
            $metaProperty::class . '" of value "' . $metaProperty->value . '"'
        );
    }

    public function __call(string $property, $arguments): mixed
    {
        $metaProperties = Meta\Reflection::metaProperties($this);

        foreach ($metaProperties as $metaProperty) {
            if ($metaProperty::method() === $property) {
                return Meta\Reflection::metaValue($metaProperty, $this);
            }
        }

        return null;
    }
}
