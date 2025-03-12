<?php

namespace App\Services\Notes;


use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Link;
use App\Models\Notes\Note;
use App\Services\TextProcess\TextPayload;
use Carbon\CarbonImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;



final class NoteLinkService
{
    public function __construct()
    {}

    public function saveFromTextPayload(
        TextPayload $payload,
        Note $note
    )
    {
        $linkIds = $note->links->pluck('id', 'id')->toArray();

        foreach ($payload->links as $link){
            if($model = $note->links->where('link', $link['link'])->first()){
                $model->name = $link['name'];

                $model->save();

                unset($linkIds[$model->id]);

            } else {
                $this->createLink($note, $link);
            }
        }

        if(!empty($linkIds)){
            Link::query()->whereIn('id', $linkIds)->delete();
        }
    }

    public function createLink(Note $note, array $data): Link
    {
        if(!empty($data['attributes'])){
            if(isset($data['attributes']['class'])){
                $data['attributes']['class'] = $data['attributes']['class'] . ' inactive';
            } else {
                $data['attributes']['class'] = 'inactive';
            }
        } else {
            $data['attributes']['class'] = 'inactive';
        }

        if($data['is_external']){
            $data['attributes']['target'] = '_blank';
        }

        $model = new Link();
        $model->note_id = $note->id;
        $model->link = $data['link'];
        $model->name = $data['name'];
        $model->is_external = $data['is_external'];
        $model->to_note_id = $data['to_id'];
        $model->active = false;
        $model->attributes = $data['attributes'];
        $model->reasons = ['system.note_links.inactive.reasons.no_check'];

        $model->save();

        return $model;
    }

    public function checkLinks(Note $model): void
    {
        $model->links->each(function(Link $link){
            $this->checkLink($link);
        });
    }

    public function checkLink(Link $model): void
    {
        if($model->is_external){
            $this->checkExternalLink($model);
        } else {
            $this->checkInnerLink($model);
        }
    }

    private function checkExternalLink(Link $model): Link
    {
        $client = new Client();

        try {
            $response = $client->get($model->link, ['timeout' => 5]);

            if($response->getStatusCode() >= 200 && $response->getStatusCode() < 300){
                $model->active = true;
                $model->attributes = ["target" => "_blank"];
                $model->reasons = [];
            } else {
                $model->active = false;
                $model->attributes = ["target" => "_blank", 'class' => 'inner_link inactive'];
                $model->reasons = [
                    "Сайт не доступен, ответ от сервера: [{$response->getBody()}]"
                ];
            }
        } catch (RequestException $e) {
            $model->active = false;
            $model->attributes = ["target" => "_blank", 'class' => 'inner_link inactive'];
            $model->reasons = [
                "Сайт не доступен, ответ от сервера: [{$e->getMessage()}]"
            ];
        }

        $model->last_check_at = CarbonImmutable::now();
        $model->save();

        return $model;
    }

    private function checkInnerLink(Link $model): Link
    {
        $reasons = [];
        $note = Note::query()
            ->where('id', $model->to_note_id)
            ->first();

        if(is_null($note)){
            $reasons[] = 'Not found linked note';
        }
        if(
            $note &&
            (
                $note->status == NoteStatus::DRAFT
                || $note->status == NoteStatus::MODERATION
            )
        ){
            $reasons[] = 'Linked note is not public';
        }

        if(empty($reasons)){
            $model->active = true;
            $model->reasons = [];
            $model->attributes = ['class' => 'inner_link'];
        } else {
            $model->active = false;
            $model->reasons = $reasons;
            $model->attributes = ['class' => 'inner_link inactive'];
        }
        $model->last_check_at = CarbonImmutable::now();
        $model->save();

        return $model;
    }
}