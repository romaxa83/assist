<?php

namespace Database\Seeders;

use App\Console\Commands\Init\SyncRoleAndPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
//        if(!App::environment('testing')) {
            Artisan::call(SyncRoleAndPermission::class);
//        }
    }
}

