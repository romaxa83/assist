<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;

final class BreakTextIntoBlocks implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        $text = $payload->originalText;

        $payload->blocks = $this->run($text);

        return $payload;
    }

    // здесь логика по разбиваем текст на блоки и возвращаем их
    public function run(string $text): array
    {
        return [];
    }
}
