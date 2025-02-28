<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    // так указываем флаг со значением
    protected $signature = 'cmd:test {--f=}';



    public function handle(): int
    {
        // так получаем
        $flag = $this->option('f');

        // при вызове php artisan cmd:test --f=20
        dd($flag); // "20"

        return self::SUCCESS;
    }
}
