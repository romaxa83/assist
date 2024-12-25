<?php

namespace Tests\Unit\Core\Permissions\Services\PermissionService;

use App\Core\Permissions\Dto\PermissionDto;
use App\Core\Permissions\Enums\GuardEnum;
use App\Core\Permissions\Models\Permission;
use App\Core\Permissions\Services\PermissionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;
    protected PermissionService $service;

    public function setUp(): void
    {
        $this->service = resolve(PermissionService::class);

        parent::setUp();
    }

    public function test_success()
    {
        $data = [
            'name' => 'user.create',
            'guard' => GuardEnum::Admin_guard()
        ];
        $dto = PermissionDto::byArgs($data);

        $this->assertEquals($dto->name, $data['name']);
        $this->assertEquals($dto->guard, $data['guard']);

        $res = $this->service->create($dto);

        $this->assertInstanceOf(Permission::class, $res);
        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->guard_name, $data['guard']);
    }
}
