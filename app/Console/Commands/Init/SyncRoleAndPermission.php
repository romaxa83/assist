<?php

namespace App\Console\Commands\Init;


use App\Core\Permissions\Services\SyncPermission;
use Illuminate\Console\Command;

class SyncRoleAndPermission extends Command
{
    protected $signature = 'app:role_and_perm';

    public function __construct(
        protected SyncPermission $service
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $start = microtime(true);

            $this->exec();

            $time = microtime(true) - $start;

            $this->info("Done [time = {$time}]");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }

    private function exec()
    {
        $this->service->exec();
    }
}
