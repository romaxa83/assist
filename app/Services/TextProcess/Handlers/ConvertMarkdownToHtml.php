<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;
use League\CommonMark\CommonMarkConverter;

final class ConvertMarkdownToHtml implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        $text = $payload->originalText;

        $converter = new CommonMarkConverter();
        $result = $converter->convert($text);

        $payload->processedText = $result;

        return $payload;
    }
}
