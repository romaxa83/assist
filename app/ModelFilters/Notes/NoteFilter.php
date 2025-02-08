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

    public function id(int|string $value): void
    {
        $this->where('id', $value);
    }

    public function status(string $value): void
    {
        $this->where('status', $value);
    }

    public function search(string $value): void
    {


    }
}

