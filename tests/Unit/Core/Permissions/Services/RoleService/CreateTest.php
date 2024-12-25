<?php

namespace Tests\Unit\Core\Permissions\Services\RoleService;

use App\Core\Permissions\Dto\RoleDto;
use App\Core\Permissions\Models\Role;
use App\Core\Permissions\Roles\AdminRole;
use App\Core\Permissions\Services\RoleService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Permissions\PermissionBuilder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    protected PermissionBuilder $permissionBuilder;
    protected RoleService $service;

    public function setUp(): void
    {
        $this->permissionBuilder = resolve(PermissionBuilder::class);
        $this->service = resolve(RoleService::class);

        parent::setUp();
    }

    public function test_success()
    {
        $role = new AdminRole();

        $perm_1 = $this->permissionBuilder->create();
        $perm_2 = $this->permissionBuilder->create();

        $data = [
            'name' => $role->getName(),
            'guard' => $role->getGuard(),
            'permission_ids' => [
                $perm_1->id,
                $perm_2->id,
            ]
        ];
        $dto = RoleDto::byArgs($data);

        $this->assertEquals($dto->name, $data['name']);
        $this->assertEquals($dto->guard, $data['guard']);
        $this->assertEquals($dto->permissionsIds, $data['permission_ids']);

        $res = $this->service->create($dto);

        $this->assertInstanceOf(Role::class, $res);

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->guard_name, $data['guard']);

        $this->assertCount(2, $res->permissions);
    }

    public function test_success_without_permission()
    {
        $role = new AdminRole();

        $data = [
            'name' => $role->getName(),
            'guard' => $role->getGuard()
        ];
        $dto = RoleDto::byArgs($data);

        $this->assertEquals($dto->name, $data['name']);
        $this->assertEquals($dto->guard, $data['guard']);
        $this->assertEmpty($dto->permissionsIds);

        $res = $this->service->create($dto);

        $this->assertInstanceOf(Role::class, $res);

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->guard_name, $data['guard']);
        $this->assertEmpty($res->permissions);
    }
}
