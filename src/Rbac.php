<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Role\HierarchicalRoleInterface;

/**
 * Class Rbac
 * @package Dot\Rbac
 */
class Rbac implements RbacInterface
{
    /**
     * @param RoleInterface|RoleInterface[]|\Traversable $roles
     * @param string $permission
     *
     * @return bool
     */
    public function isGranted(string $permission, array $roles): bool
    {
        foreach ($this->flattenRoles($roles) as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $roles
     * @return \Generator
     */
    protected function flattenRoles(array $roles): \Generator
    {
        foreach ($roles as $role) {
            yield $role;

            if (!$role instanceof HierarchicalRoleInterface) {
                continue;
            }

            $children = $this->flattenRoles($role->getChildren());

            foreach ($children as $child) {
                yield $child;
            }
        }
    }
}
