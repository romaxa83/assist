<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;
use DOMDocument;

final class GetLinksFromHtml implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        $dom = new DOMDocument();

        $dom->loadHTML($payload->processedText);

        $links = $dom->getElementsByTagName('a');

        $result = [];
        foreach ($links as $k => $link) {
            $href = $link->getAttribute('href');
            $text = $link->nodeValue;

            $result[$k] = [
                'link' =>trim($href),
                'name' => trim($text),
                'is_external' => true,
                'to_id' => null,
                'attributes' => [],
            ];

            if (
                str_starts_with($href, '/notes')
                || str_starts_with($href, 'notes'))
            {
                $result[$k]['is_external'] = false;
                $result[$k]['to_id'] = last(explode('/', $href));
                $result[$k]['attributes'] = [
                    'class' => 'inner_link',
                ];
            }
        }

        $payload->links = $result;

        return $payload;
    }
}


