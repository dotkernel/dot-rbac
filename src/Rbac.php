<?php

declare(strict_types=1);

namespace Dot\Rbac;

use Dot\Rbac\Role\HierarchicalRoleInterface;
use Generator;

class Rbac implements RbacInterface
{
    public function isGranted(string $permission, array $roles): bool
    {
        foreach ($this->flattenRoles($roles) as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    protected function flattenRoles(array $roles): Generator
    {
        foreach ($roles as $role) {
            yield $role;

            if (! $role instanceof HierarchicalRoleInterface) {
                continue;
            }

            $children = $this->flattenRoles($role->getChildren());

            foreach ($children as $child) {
                yield $child;
            }
        }
    }
}
