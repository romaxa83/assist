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

    public function create(NoteDto $dto): Note
    {
        return make_transaction(function() use ($dto) {

            $model = $this->fill(new Note(), $dto, false);
            $model->status = NoteStatus::DRAFT;
            $model->author_id = auth()->id();
            $model->weight = 0;

            $model->save();

            if(count($dto->tags) > 0){
                $model->tags()->sync($dto->tags);
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

            $model->tags()->sync($dto->tags);

            $model->refresh();

            return $model;
        });
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
//        dd($this->textProcessingService->process($dto->text));

        $model->title = $dto->title;
        $model->slug = slug($dto->title);
        $model->text = $dto->text;
//        $model->links = $dto->links;
//        [
//            'text' => $model->text,
//            'anchors' => $model->anchors,
//            'blocks' => $model->text_blocks
//        ] = $this->textProcessingService->process($dto->text);

        if ($save) $model->save();

        return $model;
    }
}
