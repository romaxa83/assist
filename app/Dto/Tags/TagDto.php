<?php

namespace App\Dto\Tags;

class TagDto
{
    public string $name;
    public ?string $color;

    public static function byArgs(array $data): self
    {
        $self = new self();

        $self->name = $data['name'];
        $self->color = $data['color'] ?? null;

        return $self;
    }
}
