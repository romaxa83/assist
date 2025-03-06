<?php

namespace App\Console\Commands\Init;

use App\Core\Permissions\Models\Role;
use App\Dto\Users\UserDto;
use App\Models\Users\User;
use App\Services\Users\UserService;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'app:create_admin';

    public function __construct(
        protected UserService $service
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $data = [
            1 => 'one',
            2 => 'two',
        ];

        $res = last($data);
        dump($res);     // two

        $res = lzast([]);
        dump($res);     //one


        dd();
//        dd($res); // 1
//
//        $res = array_key_first([]);
//        dd($res); // null
//
//
//        //        array_key_first(array $array): int|string|null
//
//        dd('g');

        try {
            $start = microtime(true);

            $this->exec();

            $time = microtime(true) - $start;

            $this->info("Done [time = {$time}]");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            dd($e);
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }

    private function exec()
    {
        $data = [
            'name' => $this->ask('Name'),
            'email' => $this->ask('Email'),
            'password' => $this->ask('Password'),
        ];

        if(User::query()->where('email', $data['email'])->exists()) {
            throw new \Exception("User already exists");
        }

        $data['role'] = Role::query()->admin()->first();
        $dto = UserDto::byArgs($data);

        $this->service->create($dto);
    }
}
