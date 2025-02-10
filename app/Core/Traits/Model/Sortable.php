<?php

namespace App\Core\Traits\Model;

use App\Enums\OrderType;

trait Sortable
{
    public static function getSortEnums(): array
    {
        $tmp = [];
        foreach (self::allowedSortFields() as $field) {
            $tmp[] = "{$field}-".OrderType::ASC();
            $tmp[] = "{$field}-".OrderType::DESC();
        }

        return $tmp;
    }

    public static function getSortEnumsAsString(string $separator = ', '): string
    {
        return implode($separator, self::getSortEnums());
    }

    public static function getDefaultSortEnum(): string
    {
        return self::DEFAULT_SORT_FIELD .'-'.self::DEFAULT_SORT_TYPE;
    }
}

