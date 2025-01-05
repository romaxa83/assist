<?php

namespace App\Collections;

use App\Models\Tags\Tag;
use ArrayIterator;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Tag|null first(callable $callback = null, $default = null)
 * @method Tag|null last(callable $callback = null, $default = null)
 * @method Tag|null pop()
 * @method Tag|null shift()
 * @method ArrayIterator|Tag[] getIterator()
 */
class TagCollection extends Collection
{
    public function __construct(array $items = [])
    {
        usort($items, function ($a, $b) {
            return $b->weight <=> $a->weight;
        });
        parent::__construct($items);
    }

    public function getNamesAsString(): string
    {
        $names = [];

        foreach ($this->items as $tag) {
            $names[] = $tag->name;
        }

        return implode(', ', $names);
    }

    public function increaseWeights(): self
    {
        array_map(fn (Tag $tag) => $tag->increaseWeight(), $this->items);

        return $this;
    }

    public function decreaseWeights(): self
    {
        array_map(fn (Tag $tag) => $tag->decreaseWeight(), $this->items);

        return $this;
    }
}