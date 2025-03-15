<?php

namespace App\Services\Notes;

use App\Models\Notes\Block;
use App\Models\Notes\Note;
use App\Services\TextProcess\TextPayload;

final class NoteBlockService
{
    public function __construct()
    {}

    public function saveFromTextPayload(
        TextPayload $payload,
        Note $note
    )
    {
        $blockIds = $note
            ->blocks
            ->pluck('id', 'id')
            ->toArray();

        foreach ($payload->blocks as $key => $block){

            if($model = $note->blocks->where('content', $block['content'])->first()){
                $model->position = $key;

                $model->save();

                unset($blockIds[$model->id]);

            } else {

                $model = new Block();
                $model->note_id = $note->id;
                $model->type = $block['type'];
                $model->lang = $block['language'];
                $model->content = $block['content'];
                $model->position = $key;
                $model->is_collapse = false;
                $model->save();
            }
        }

        if(!empty($blockIds)){
            Block::query()->whereIn('id', $blockIds)->delete();
        }
    }
}