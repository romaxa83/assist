<?php

namespace App\Services\TextProcess;

final class TextProcessingPipeline
{
    private array $handlers = [];

    public function addHandler(TextProcessorHandler $handler): self
    {
        $this->handlers[] = $handler;
        return $this;
    }

    public function process(TextPayload $payload): TextPayload
    {
        foreach ($this->handlers as $handler) {
            $payload = $handler->handle($payload);
        }

        return $payload;
    }
}
