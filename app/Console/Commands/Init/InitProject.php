<?php

namespace App\Console\Commands\Init;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitProject extends Command
{
    protected $signature = 'app:init';

    public function handle()
    {
        $this->info('sync role and permissions');
        Artisan::call(SyncRoleAndPermission::class);
    }
}
