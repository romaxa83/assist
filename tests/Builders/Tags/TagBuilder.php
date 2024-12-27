<?php

namespace Tests\Builders\Tags;

use App\Models\Tags\Tag;
use Tests\Builders\BaseBuilder;

class TagBuilder extends BaseBuilder
{
    function modelClass(): string
    {
        return Tag::class;
    }

    public function name(string $value): self
    {
        $this->data['name'] = $value;
        return $this;
    }

    public function weight(int $value): self
    {
        $this->data['weight'] = $value;
        return $this;
    }
}

