<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
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
