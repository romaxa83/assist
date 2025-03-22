<?php

namespace App\Console\Commands;

use App\Core\Enums\MetaProperties\Color;
use App\Enums\Test\StatusBackendEnum;
use App\Enums\Test\StatusPurpleEnum;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    // так указываем флаг со значением
    protected $signature = 'cmd:test {--f=}';

    public function handle(): int
    {


        dd(
            StatusBackendEnum::Draft->in([StatusBackendEnum::Draft, StatusBackendEnum::New]),
            StatusBackendEnum::Draft->in([StatusBackendEnum::Closed, StatusBackendEnum::New]),
//            StatusBackendEnum::Draft->color(),
//            StatusBackendEnum::fromMeta(Color::make('green')),

//            StatusPurpleEnum::Draft()
        );

        return self::SUCCESS;
    }
}