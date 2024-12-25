<?php

namespace Tests\Unit\Core\Permissions\Services\PermissionService;

use App\Core\Permissions\Dto\PermissionDto;
use App\Core\Permissions\Enums\GuardEnum;
use App\Core\Permissions\Models\Permission;
use App\Core\Permissions\Services\PermissionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Permissions\PermissionBuilder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    protected PermissionBuilder $permissionBuilder;
    protected PermissionService $service;

    public function setUp(): void
    {
        $this->permissionBuilder = resolve(PermissionBuilder::class);
        $this->service = resolve(PermissionService::class);

        parent::setUp();
    }

    public function test_update()
    {
        /** @var $model Permission */
        $model = $this->permissionBuilder->create();

        $data = [
            'name' => 'user.create',
            'guard' => GuardEnum::Admin_guard()
        ];
        $dto = PermissionDto::byArgs($data);

        $this->assertNotEquals($model->name, $data['name']);

        $res = $this->service->update($model, $dto);

        $this->assertInstanceOf(Permission::class, $res);

        $model->refresh();

        $this->assertEquals($model->name, $data['name']);
    }
}
