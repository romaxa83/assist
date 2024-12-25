<?php

namespace App\Core\Permissions\Services;

use App\Core\Permissions\Dto\RoleDto;
use App\Core\Permissions\Models\Permission;
use App\Core\Permissions\Models\Role;
use App\Core\Permissions\Roles\DefaultRole;
use Illuminate\Database\Eloquent\Collection;

class SyncPermission
{
    public function __construct(
        protected PermissionService $permissionService,
        protected RoleService $roleService,
    )
    {}

    public function exec()
    {
        try {
            $this->createDefaultRoles();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    private function createDefaultRoles()
    {
        // получаем все пермишины из бд
        $perms = Permission::all();

        foreach (Role::defaultRoles() as $class){

            if(!class_exists($class)) continue;
            /** @var DefaultRole $defaultRole */
            $defaultRole = resolve($class);

            /** @var Role $role */
            $role = Role::query()->where([
                ['name', $defaultRole->getName()],
                ['guard_name', $defaultRole->getGuard()]
            ])->first();

            $dto = RoleDto::byArgs([
                'name' => $defaultRole->getName(),
                'guard' => $defaultRole->getGuard(),
                'permission_ids' => $this->permissionIdsForRole(
                    $defaultRole,
                    $perms
                ),
            ]);

            if($role){
                $this->roleService->update($role, $dto);
            } else {
                $this->roleService->create($dto);
            }
        }
    }

    private function permissionIdsForRole(
        DefaultRole $defaultRole,
        Collection $permissions,
    ): array
    {
        // получаем прописанные пермишины для роли
        $permissionsForRole = [];
        foreach ($defaultRole->getPermissions() as $permissionClasses){
            foreach ($permissionClasses as $permissionClass){
                $permissionsForRole[] = $permissionClass::KEY;
            }
        }

        // получаем из бд те пермишены относятся к этой роли
        $permissionDB = $permissions
            ->where('guard_name', $defaultRole->getGuard())
            ->whereIn('name', $permissionsForRole)
            ->pluck('name', 'id')
            ->toArray()
        ;

        // получаем расхождение, т.е. пермишены для роли которых нет в бд
        $permDiff = array_diff($permissionsForRole, $permissionDB);
        if(!empty($permDiff)){
            // создаем эти пермишены
            $upsertData = [];
            foreach ($permDiff as $item){
                $upsertData[] = [
                    'guard_name' => $defaultRole->getGuard(),
                    'name' => $item,
//                    'group' => current(explode('.', $item)),
                ];
            }

            Permission::query()->upsert($upsertData, ['name', 'guard_name']);

            $newPerms = Permission::query()
                ->where('guard_name', $defaultRole->getGuard())
                ->whereIn('name', $permDiff)
                ->get();

            // добавляем новые пермишены к существующим
            $permissionDB = $permissionDB + $newPerms->pluck('name', 'id')->toArray();

        }

        return array_keys($permissionDB);
    }
}



