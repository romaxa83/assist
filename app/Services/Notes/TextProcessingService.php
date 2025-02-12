<?php

namespace App\Services\Notes;

use Illuminate\Support\Str;

final class TextProcessingService
{
    public function __construct()
    {}


    public function addAnchorsToText(string|null $text): array
    {
        $anchors = [];

        // обрабатываем заголовки - h2, h3, h4, h5, h6
        $pattern = '/<(h[2-6])>(.*?)<\/\1>/i';

        // Callback функция для добавления якорей
        $modifiedText = preg_replace_callback($pattern, function ($matches) use (&$anchors) {
            $tag = $matches[1];
            $contentWithTags = $matches[2];

            // Убрать вложенные теги для получения текста без HTML
            $plainContent = strip_tags($contentWithTags);

            // Генерация уникального ID из содержимого в теги
            $id = Str::slug($plainContent);

            // Добавляем информацию в массив навигации
            $anchors[] = [
                'tag' => $tag,
                'id' => $id,
                'content' => $plainContent,
            ];

            // Возвращаем модифицированный заголовок с якорем
            return "<$tag id=\"$id\">$contentWithTags</$tag>";
        }, $text);

        return [
            'text' => $modifiedText,
            'anchors' => $anchors
        ];
    }

}
