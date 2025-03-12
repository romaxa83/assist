<?php

namespace Tests\Builders\Notes;

use App\Models\Notes\Link;
use App\Models\Notes\Note;
use Carbon\CarbonImmutable;
use Tests\Builders\BaseBuilder;

class LinkBuilder extends BaseBuilder
{
    function modelClass(): string
    {
        return Link::class;
    }

    public function note(Note $model): self
    {
        $this->data['note_id'] = $model->id;
        return $this;
    }

    public function to_note(Note $model): self
    {
        $this->data['to_note_id'] = $model->id;
        return $this;
    }

    public function link(string $value): self
    {
        $this->data['link'] = $value;
        return $this;
    }

    public function name(string $value): self
    {
        $this->data['name'] = $value;
        return $this;
    }

    public function last_check_at(CarbonImmutable $value): self
    {
        $this->data['last_check_at'] = $value;
        return $this;
    }
}