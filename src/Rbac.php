<?php

declare(strict_types=1);

namespace Dot\Rbac;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Role\PermissionRoleInterface;
use Dot\Rbac\Role\RoleFlattener;
use Generator;

class Rbac implements RbacInterface
{
    /**
     * @param array<RoleInterface> $roles
     */
    public function isGranted(string $permission, array $roles): bool
    {
        foreach ($this->flattenRoles($roles) as $role) {
            if ($role instanceof PermissionRoleInterface && $role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<RoleInterface> $roles
     * @return Generator<int, RoleInterface>
     */
    protected function flattenRoles(array $roles): Generator
    {
        return RoleFlattener::flatten($roles);
    }
}
