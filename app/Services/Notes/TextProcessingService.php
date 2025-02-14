<?php

namespace App\Services\Notes;

use Illuminate\Support\Str;

final class TextProcessingService
{
    public function __construct()
    {}

    public function process(string|null $text): array
    {
        [
            'text' => $processedText,
            'anchors' => $anchors
        ] = $this->addAnchorsToText($text);

        $textBlocks = $this->getTextBlocks($processedText);

        return [
            'text' => $processedText,
            'anchors' => $anchors,
            'blocks' => $textBlocks,
        ];

    }

    /**
     * Метод для обработки текста и разделения его на блоки текста и кода.
     *
     * @param string|null $text
     * @return array
     */
    public function getTextBlocks(null|string $text): array
    {
        if (is_null($text)) {
            return [];
        }

        $blocks = [];

        // Шаблон для поиска блоков <pre><code>...</code></pre> и разделения текста
        $pattern = '/<pre><code>{{\s*(\w*)\s*}}(.*?)<\/code><\/pre>/s';

        $splitParts = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        for ($i = 0; $i < count($splitParts); $i++) {
            if ($i % 3 === 0) {
                // Обычный текстовый блок
                $blocks[] = [
                    'type' => 'text',
                    'language' => 'text/html',
                    'content' => $splitParts[$i],
                ];
            } elseif ($i % 3 === 1) {
                // Язык блока кода
                $language = $splitParts[$i] ?: 'plain';
                $blocks[] = [
                    'type' => 'code',
                    'language' => $language,
                    'content' => html_entity_decode(trim($splitParts[$i + 1])),
                ];
            }
        }

        return $blocks;
    }

    public function addAnchorsToText(string|null $text): array
    {
        $anchors = [];

        // Регулярное выражение для поиска заголовков (h2 - h6) с возможным атрибутом id
/*        $pattern = '/<(h[2-6])(?:\s+[^>]*)?(?:id="[^"]*")?>(.*?)<\/\1>/i';*/
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
