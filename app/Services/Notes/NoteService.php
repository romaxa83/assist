<?php

namespace App\Services\Notes;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;

final class NoteService
{
    public function __construct(
        protected TextProcessingService $textProcessingService
    )
    {}

    public function create(NoteDto $dto,): Note
    {
        return make_transaction(function() use ($dto) {

            $model = $this->fill(new Note(), $dto, false);
            $model = $this->setStatus($model, NoteStatus::DRAFT, false);
            $model->author_id = auth()->id();
            $model->weight = 0;

            $model->save();

            if(count($dto->tags) > 0){
                $model->tags()->sync($dto->tags);

                $model->tags->increaseWeights();
            }

            return $model;
        });
    }

    public function update(
        Note $model,
        NoteDto $dto,
    ): Note
    {
        return make_transaction(function() use ($model, $dto) {

            $model = $this->fill($model, $dto);

            $model->tags->decreaseWeights();
            $model->tags()->sync($dto->tags);

            $model->refresh();

            $model->tags->increaseWeights();

            return $model;
        });
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
        $model->tags->decreaseWeights();

        return $model->delete();
    }

    private function fill(
        Note $model,
        NoteDto $dto,
        bool $save = true
    ): Note
    {
//        dd($this->textProcessingService->process($dto->text));

        $model->title = $dto->title;
        $model->slug = slug($dto->title);
        $model->links = $dto->links;
        [
            'text' => $model->text,
            'anchors' => $model->anchors,
            'blocks' => $model->text_blocks
        ] = $this->textProcessingService->process($dto->text);

        if ($save) $model->save();

        return $model;
    }
}
