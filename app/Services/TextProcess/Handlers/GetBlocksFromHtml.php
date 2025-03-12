<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;

// Метод для обработки текста и разделения его на блоки текста и кода.
final class GetBlocksFromHtml implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        $text = $payload->processedText;

        // Регулярное выражение для поиска:
        // 1. Блоков кода (<pre><code>) с языком или без.
        // 2. Обычной HTML-разметки между блоками кода.
        $pattern = '/<pre><code(?:\s+[^>]*class=["\'][^"]*language-(\w+)["\'][^>]*)?[^>]*>(.*?)<\/code><\/pre>|(.+?)(?=<pre>|$)/s';

        // Извлекаем подходящие части
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $blocks = [];

        foreach ($matches as $match) {
            if(!trim(last($match))) continue;

            if(isset($match[0]) && str_starts_with($match[0], '<pre><code')){

                $lang = 'plaintext';
                if(array_key_last($match) > 0){
                    $preLastKey = array_key_last($match) - 1;

                    if(isset($match[$preLastKey]) && $match[$preLastKey]){
                        $lang = $match[$preLastKey];
                    }
                }

                $blocks[] = [
                    'type' => 'code',
                    'language' => $lang,
                    'content' => trim(last($match)),
                ];
            } else {
                $blocks[] = [
                    'type' => 'text',
                    'language' => 'html/text',
                    'content' => trim(last($match)),
                ];
            }
        }

        $payload->blocks = $blocks;

        return $payload;
    }
}


