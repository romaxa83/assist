<?php

namespace Tests\Builders\Notes;

use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Tests\Builders\BaseBuilder;

class NoteBuilder extends BaseBuilder
{
    protected array $tags = [];

    function modelClass(): string
    {
        return Note::class;
    }

    public function title(string $value): self
    {
        $this->data['title'] = $value;
        return $this;
    }

    public function text(string $value): self
    {
        $this->data['text'] = $value;
        return $this;
    }

    public function weight(int $value): self
    {
        $this->data['weight'] = $value;
        return $this;
    }

    public function tags(Tag ...$models): self
    {
        $this->tags = $models;
        return $this;
    }

    protected function afterSave($model): void
    {
        /** @var $model Note */
        if(!empty($this->tags)){
            $ids = [];
            foreach ($this->tags as $tag){
                $ids[] = $tag->id;
            }

            $model->tags()->sync($ids);
        }
    }

    protected function afterClear(): void
    {
        $this->tags = [];
    }
}