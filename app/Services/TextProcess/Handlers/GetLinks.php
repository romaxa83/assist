<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;

final class GetLinks implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        // в текст добавляем линкам класс
        $payload->processedText = $this->addClassIntoText($payload->originalText);
        // получаем все линки из текста
        $payload->links = $this->getLinks($payload->originalText);

        return $payload;
    }

    public function getLinks(string $text): array
    {
        // логика
        return [];
    }

    public function addClassIntoText(string $text): string
    {
        // логика
        return $text;
    }
}
