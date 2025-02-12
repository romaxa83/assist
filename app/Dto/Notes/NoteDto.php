<?php

namespace App\Dto\Notes;

class NoteDto
{
    public string $title;
    public ?string $text;
    public array $tags;
    public array $links;

    public static function byArgs(array $data): self
    {
        $self = new self();

        $self->title = $data['title'];
        $self->text = $data['text'] ?? null;
        $self->tags = $data['tags'] ?? [];
        $self->links = $data['links'] ?? [];

        return $self;
    }
}
