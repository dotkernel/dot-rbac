<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Exception\RuntimeException;
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
    public function isGranted($roles, $permission)
    {
        if (!is_string($permission)) {
            throw new RuntimeException(sprintf(
                'Permission must be a string, "%s" given',
                is_object($permission) ? get_class($permission) : gettype($permission)
            ));
        }

        if ($roles instanceof RoleInterface) {
            $roles = [$roles];
        }

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
    protected function flattenRoles($roles)
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
