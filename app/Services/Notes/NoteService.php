<?php

namespace App\Services\Notes;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use App\Services\TextProcess\TextProcessService;

final class NoteService
{
    public function __construct(
        protected TextProcessService $textProcessService,
        protected NoteLinkService $noteLinkService,
        protected NoteBlockService $noteBlockService,
    )
    {}

    public function create(NoteDto $dto): Note
    {
        return make_transaction(function() use ($dto) {

            $model = $this->fill(new Note(), $dto, false);
            $model->status = NoteStatus::DRAFT;
            $model->author_id = auth()->id();
            $model->weight = 0;

            $payload = $this->textProcessService
                ->run($dto->text);

            $model->text_html = $payload->processedText;
            $model->anchors = $payload->anchors;
            $model->text_blocks = $payload->blocks;

            $model->save();

            if(count($dto->tags) > 0){
                $model->tags()->sync($dto->tags);
            }

            $this->noteLinkService->saveFromTextPayload($payload, $model);

            $this->noteBlockService->saveFromTextPayload($payload, $model);

            return $model;
        });
    }

    public function update(
        Note $model,
        NoteDto $dto,
    ): Note
    {
        return make_transaction(function() use ($model, $dto) {

            $model = $this->fill($model, $dto, false);

//            $model->tags()->sync($dto->tags);

            $payload = $this->textProcessService
                ->run($dto->text);

            $model->text_html = $payload->processedText;
            $model->anchors = $payload->anchors;
            $model->text_blocks = $payload->blocks;

            $model->save();

            $this->noteLinkService->saveFromTextPayload($payload, $model);

            $this->noteBlockService->saveFromTextPayload($payload, $model);

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
        $model->title = $dto->title;
        $model->slug = slug($dto->title);
        $model->text = $dto->text;

        if ($save) $model->save();

        return $model;
    }
}
