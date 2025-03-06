<?php

namespace App\Core\Contracts\Model;

interface Sortable
{
    public static function allowedSortFields(): array;

    public static function getSortEnums(): array;
    public static function getSortEnumsAsString(): string;
    public static function getDefaultSortEnum(): string;
}

