<?php

namespace Tests\Builders\Notes;

use App\Models\Notes\Note;
use Tests\Builders\BaseBuilder;

class NoteBuilder extends BaseBuilder
{
    function modelClass(): string
    {
        return Note::class;
    }

    public function title(string $value): self
    {
        $this->data['title'] = $value;
        return $this;
    }

    public function weight(int $value): self
    {
        $this->data['weight'] = $value;
        return $this;
    }
}

