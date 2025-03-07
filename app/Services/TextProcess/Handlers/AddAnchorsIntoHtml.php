<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;
use Illuminate\Support\Str;

final class AddAnchorsIntoHtml implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        $result = $this->exec($payload->processedText);

        $payload->processedText = $result['text'];
        $payload->anchors = $result['anchors'];

        return $payload;
    }

    private function exec(string $text): array
    {
        $anchors = [];

        // Регулярное выражение для поиска заголовков (h2 - h6) с возможным атрибутом id
        $pattern = '/<(h[2-6])([^>]*)>(.*?)<\/\1>/i';

        // Callback функция для добавления якорей
        $modifiedText = preg_replace_callback($pattern, function ($matches) use (&$anchors) {
            $tag = $matches[1];
            $attributes = $matches[2];
            $contentWithTags = $matches[3];

            // Убрать вложенные теги для получения текста без HTML
            $plainContent = strip_tags($contentWithTags);

            // Генерация уникального ID из содержимого в теги
            $id = Str::slug($plainContent);

            // Если у заголовка уже есть id, мы его заменим. Если id нет, добавим его.
            if (preg_match('/\bid="[^"]*"/i', $attributes)) {
                // Заменяем существующий id на новый
                $attributes = preg_replace('/\bid="[^"]*"/i', "id=\"$id\"", $attributes);
            } else {
                // Добавляем id, если его нет
                $attributes = trim($attributes) . " id=\"$id\"";
            }

            // Добавляем информацию в массив навигации
            $anchors[] = [
                'tag' => $tag,
                'id' => $id,
                'content' => $plainContent,
            ];

            // Возвращаем модифицированный заголовок с якорем
            return "<$tag $attributes>$contentWithTags</$tag>";
        }, $text);

        return [
            'text' => $modifiedText,
            'anchors' => $anchors
        ];
    }
}

