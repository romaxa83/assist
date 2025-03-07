<?php

namespace App\Services\TextProcess;


final class TextProcessService
{
    public function __construct()
    {}

    public function run(string $text): TextPayload
    {
        $payload = new TextPayload($text);

        $pipeline = (new TextProcessingPipeline())
            ->addHandler(new Handlers\ConvertMarkdownToHtml()) // текст из markdown конвертируем в html
            ->addHandler(new Handlers\GetLinksFromHtml())
            ->addHandler(new Handlers\AddAnchorsIntoHtml())     // создаем массив с якорями по тексту (на основе заголовков)
        ;

        return $pipeline->process($payload);
    }
}