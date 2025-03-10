<?php

namespace App\Services\Notes;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Link;
use App\Models\Notes\Note;
use App\Services\TextProcess\TextProcessService;

final class NoteService
{
    public function __construct(
        protected TextProcessService $textProcessService
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

            $model = $this->fill($model, $dto, false);

//            $model->tags()->sync($dto->tags);

            $payload = $this->textProcessService
                ->run($dto->text);

            $model->text_html = $payload->processedText;
            $model->text_blocks = $payload->blocks;
            $model->anchors = $payload->anchors;

            $model->save();

            foreach ($payload->links as $link){
                if($linkModel = $model->links->where('link', $link['link'])->first()){
                    $linkModel->link = $link['link'];
                    $linkModel->name = $link['name'];
                    $linkModel->is_external = $link['is_external'];
                    $linkModel->to_note_id = $link['to_id'];
                    $linkModel->active = true;
                    $linkModel->attributes = $link['attributes'];
                    $linkModel->save();
                } else {
                    $linkModel = new Link();
                    $linkModel->note_id = $model->id;
                    $linkModel->link = $link['link'];
                    $linkModel->name = $link['name'];
                    $linkModel->is_external = $link['is_external'];
                    $linkModel->to_note_id = $link['to_id'];
                    $linkModel->active = true;
                    $linkModel->attributes = $link['attributes'];
                    $linkModel->save();
                }
            }

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
