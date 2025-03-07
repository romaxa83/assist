<?php

namespace App\Services\TextProcess;

final class TextPayload
{
    public string $originalText;
    public string $processedText;
    public array $links = [];
    public array $blocks = [];
    public array $anchors = [];

    public function __construct(string $text)
    {
        $this->originalText = $text;
        $this->processedText = $text;
    }
}