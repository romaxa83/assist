<?php

namespace App\Services\Notes;

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
            $content = $matches[2];

            // Генерация уникального ID из содержимого в теги
            $id = strtolower(preg_replace('/[^a-z0-9]+/', '-', strip_tags($content)));

            // Добавляем информацию в массив навигации
            $anchors[] = [
                'tag' => $tag,
                'id' => $id,
                'content' => $content,
            ];

            // Возвращаем модифицированный заголовок с якорем
            return "<$tag id=\"$id\">$content</$tag>";
        }, $text);

        return [
            'text' => $modifiedText,
            'anchors' => $anchors
        ];
    }

}
