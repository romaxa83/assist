<?php

namespace App\Services\Notes;


use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Link;
use App\Models\Notes\Note;
use App\Services\TextProcess\TextPayload;
use Carbon\CarbonImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\VarDumper\Caster\LinkStub;


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

    // проверяем привязанные ссылки заметки которая снята с публикации
    public function linkedNoteRemovePublic(Note $model): void
    {
        $links = Link::query()
            ->where('to_note_id', $model->id)
            ->where('active', true)
            ->get();

        foreach ($links as $link){
            $link->active = false;
            $link->last_check_at = CarbonImmutable::now();
            $link->attributes = ['class' => 'inner_link inactive'];
            $link->reasons = array_merge($link->reasons, ['system.note_links.warning.reasons.linked_note_not_public']);
            $link->save();
        }
    }

    public function replaceLinksInText(Note $model): void
    {
        foreach ($model->links as $link){
            /** @var Link $link */

            $newLink = $link->getAsTag();

            // Регулярное выражение для нахождения тега <a> с указанной ссылкой
            $pattern = '/<a\s+[^>]*?href="' . preg_quote($link->link, '/') . '".*?>/';

            // меняем в html
            $newText = preg_replace($pattern, $newLink, $model->text_html);

            $model->text_html = $newText;
            $model->save();

            // меняем в блоках
            foreach ($model->blocks as $block){
                if($block->type == 'text'){
                    $newContent = preg_replace($pattern, $newLink, $block->content);
                    $block->content = $newContent;
                    $block->save();
                }
            }
        }
    }

    public function checkLinks(Note $model): void
    {
        $model->links->each(function(Link $link){
            $this->checkLink($link);
        });

        $model->refresh();

        $this->replaceLinksInText($model);
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
                    'system.note_links.warning.reasons.site_is_available'
                ];
            }
        } catch (RequestException $e) {
            $model->active = false;
            $model->attributes = ["target" => "_blank", 'class' => 'inner_link inactive'];
            $model->reasons = [
                'system.note_links.warning.reasons.site_is_available'
            ];
//            $model->reasons = [
//                "Сайт не доступен, ответ от сервера: [{$e->getMessage()}]"
//            ];
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
            $reasons[] = 'system.note_links.warning.reasons.linked_note_not_found';
        }
        if(
            $note &&
            (
                $note->status == NoteStatus::DRAFT
                || $note->status == NoteStatus::MODERATION
            )
        ){
            $reasons[] = 'system.note_links.warning.reasons.linked_note_not_public';
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