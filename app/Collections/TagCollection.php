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

    public function increasePublicAttached(bool $save = true): self
    {
        array_map(fn (Tag $tag) => $tag->increasePublicAttached($save), $this->items);

        return $this;
    }

    public function decreasePublicAttached(bool $save = true): self
    {
        array_map(fn (Tag $tag) => $tag->decreasePublicAttached($save), $this->items);

        return $this;
    }

    public function increasePrivateAttached(bool $save = true): self
    {
        array_map(fn (Tag $tag) => $tag->increasePrivateAttached($save), $this->items);

        return $this;
    }

    public function decreasePrivateAttached(bool $save = true): self
    {
        array_map(fn (Tag $tag) => $tag->decreasePrivateAttached($save), $this->items);

        return $this;
    }
}