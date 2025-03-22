<?php

namespace App\Services\TextProcess\Handlers;

use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessorHandler;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

final class ConvertMarkdownToHtml implements TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload
    {
        $text = $payload->originalText;

        $config = [
            'table' => [
                'wrap' => [
                    'enabled' => false,
                    'tag' => 'div',
                    'attributes' => [],
                ],
                'alignment_attributes' => [
                    'left'   => ['align' => 'left'],
                    'center' => ['align' => 'center'],
                    'right'  => ['align' => 'right'],
                ],
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());

        $environment->addExtension(new TableExtension());

        $converter = new MarkdownConverter($environment);
        $result = $converter->convert($text);

//        $converter = new CommonMarkConverter();
//        $result = $converter->convert($text);

        $payload->processedText = $result;




        return $payload;
    }
}
