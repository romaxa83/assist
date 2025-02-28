<?php

namespace App\Services\Tags;

use App\Dto\Tags\TagDto;
use App\Models\Tags\Tag;

final class TagService
{
    public function __construct()
    {}

    public function create(
        TagDto $dto,
    ): Tag
    {
        $model = $this->fill(new Tag(), $dto, false);
        $model->public_attached = 0;
        $model->private_attached = 0;
        $model->save();

        return $model;
    }

    public function update(
        Tag $model,
        TagDto $dto,
    ): Tag
    {
        return $this->fill($model, $dto);
    }

    public function delete(
        Tag $model,
    ): bool
    {
        if($model->private_attached > 0){
            throw new \Exception(__('system.tags.actions.failed.delete.has_notes'));
        }

        return $model->delete();
    }

    private function fill(
        Tag $model,
        TagDto $dto,
        bool $save = true
    ): Tag
    {
        $model->name = $dto->name;
        $model->slug = slug($dto->name);
        $model->color = $dto->color;

        if ($save) $model->save();

        return $model;
    }
}
