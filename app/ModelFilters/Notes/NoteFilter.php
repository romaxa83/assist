<?php

namespace App\ModelFilters\Notes;

use App\ModelFilters\BaseModelFilter;
use Illuminate\Database\Eloquent\Builder;

class NoteFilter extends BaseModelFilter
{
    public function tags(array $value): void
    {
        $this->whereHas('tags',
            fn(Builder $query) => $query->whereIn('id', $value)
        );
    }

    public function search(string $value): void
    {


    }
}

