<?php

namespace App\ModelFilters\Notes;

use App\Enums\DateFormat;
use App\ModelFilters\BaseModelFilter;
use Carbon\CarbonImmutable;
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

    public function status(string|array $value): void
    {
        if(is_array($value)){
            $this->whereIn('status', $value);
        } else {
            $this->where('status', $value);
        }
    }

    public function startDate(string $value): void
    {
        $date = CarbonImmutable::createFromFormat(DateFormat::FRONT_FILTER(), $value)
            ->startOfDay()
            ->setTimezone('UTC')
        ;
        $this->where('created_at', '>=', $date);
    }

    public function endDate(string $value): void
    {
        $date = CarbonImmutable::createFromFormat(DateFormat::FRONT_FILTER(), $value)
            ->endOfDay()
            ->setTimezone('UTC')
        ;
        $this->where('created_at', '<=', $date);
    }

    public function search(string $value): void
    {


    }
}

