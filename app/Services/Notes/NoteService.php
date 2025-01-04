<?php

namespace App\Services\Notes;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;

final class NoteService
{
    public function __construct()
    {}

    public function create(
        NoteDto $dto,
    ): Note
    {
        $model = $this->fill(new Note(), $dto, false);
        $model = $this->setStatus($model, NoteStatus::Draft, false);

        $model->save();

        return $model;
    }

    public function update(
        Note $model,
        NoteDto $dto,
    ): Note
    {
        return $this->fill($model, $dto);
    }

    public function setStatus(
        Note $model,
        NoteStatus $status,
        bool $save = true
    ): Note
    {
        $model->status = $status;

        if ($save) $model->save();

        return $model;
    }

    public function delete(
        Note $model,
    ): bool
    {
        return $model->delete();
    }

    private function fill(
        Note $model,
        NoteDto $dto,
        bool $save = true
    ): Note
    {
        $model->title = $dto->title;
        $model->slug = slug($dto->title);
        $model->text = $dto->text;

        if ($save) $model->save();

        return $model;
    }
}
