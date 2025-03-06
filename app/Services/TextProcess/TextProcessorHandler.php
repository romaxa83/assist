<?php

namespace App\Services\TextProcess;

interface TextProcessorHandler
{
    public function handle(TextPayload $payload): TextPayload;
}
