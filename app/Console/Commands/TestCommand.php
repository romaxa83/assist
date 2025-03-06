<?php

namespace App\Console\Commands;

use App\Services\TextProcess\Handlers\BreakTextIntoBlocks;
use App\Services\TextProcess\Handlers\GetLinks;
use App\Services\TextProcess\TextPayload;
use App\Services\TextProcess\TextProcessingPipeline;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    // так указываем флаг со значением
    protected $signature = 'cmd:test {--f=}';

    public function handle(): int
    {
        $pipeline = new TextProcessingPipeline();
        $pipeline
            ->addHandler(new GetLinks())
            ->addHandler(new BreakTextIntoBlocks());

        $text = "<h2>Title</h2>";
        $payload = new TextPayload($text);

        $result = $pipeline->process($payload);

        dd($pipeline->process($payload));


        return self::SUCCESS;
    }
}