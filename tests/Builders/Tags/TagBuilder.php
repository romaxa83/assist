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

    public function public_attached(int $value): self
    {
        $this->data['public_attached'] = $value;
        return $this;
    }

    public function private_attached(int $value): self
    {
        $this->data['private_attached'] = $value;
        return $this;
    }

    public function all_attached(int $value): self
    {
        $this->data['all_attached'] = $value;
        return $this;
    }
}

