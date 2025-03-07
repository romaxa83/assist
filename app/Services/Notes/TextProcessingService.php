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
        $patternCode = '/<pre><code>{{\s*(\w*)\s*}}(.*?)<\/code><\/pre>/s';
        $patternQuote = '/<blockquote>(.*?)<\/blockquote>/s';


        $splitParts = preg_split($patternCode, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);



        for ($i = 0; $i < count($splitParts); $i++) {
            if ($i % 3 === 0) {
                // Внутри обычного текста ищем блоки <blockquote>
                if (preg_match_all($patternQuote, $splitParts[$i], $quoteMatches, PREG_OFFSET_CAPTURE)) {
                    $lastOffset = 0;

                    foreach ($quoteMatches[0] as $key => $quoteMatch) {
                        $offset = $quoteMatch[1];

                        // Добавляем текст до цитаты (если есть).
                        $textBeforeQuote = substr($splitParts[$i], $lastOffset, $offset - $lastOffset);
                        if (!empty(trim($textBeforeQuote))) {
                            $blocks[] = [
                                'type' => 'text',
                                'language' => 'text/html',
                                'content' => $textBeforeQuote,
                            ];
                        }

                        // Обрабатываем блок <blockquote>
                        $quoteContent = $quoteMatch[0];

                        // Ищем конструкцию {{update}}
                        if (preg_match('/{{\s*(\w+)\s*}}/', $quoteContent, $match)) {
                            $class = 'blockquote-' . $match[1]; // Создаем класс blockquote-update
                            $quoteContent = str_replace($match[0], '', $quoteContent); // Убираем {{update}} из цитаты

                            // Добавляем класс в тег <blockquote>
                            $quoteContent = preg_replace(
                                '/<blockquote(.*?)>/',
                                '<blockquote class="' . htmlspecialchars($class) . '"$1>',
                                $quoteContent
                            );
                        }

                        $blocks[] = [
                            'type' => 'quote',
                            'language' => 'text/html',
                            'content' => trim($quoteContent),
                        ];

                        $lastOffset = $offset + strlen($quoteMatch[0]);
                    }

                    // Добавляем оставшийся текст после последней цитаты (если есть).
                    $textAfterQuote = substr($splitParts[$i], $lastOffset);
                    if (!empty(trim($textAfterQuote))) {
                        $blocks[] = [
                            'type' => 'text',
                            'language' => 'text/html',
                            'content' => $textAfterQuote,
                        ];
                    }
                } else {
                    // Если в тексте нет цитат <blockquote>, добавляем весь текст как один блок.
                    $blocks[] = [
                        'type' => 'text',
                        'language' => 'text/html',
                        'content' => $splitParts[$i],
                    ];
                }
            } elseif ($i % 3 === 1) {
                // Если это блок кода <pre><code>
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
