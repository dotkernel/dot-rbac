<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role\Provider;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Role;

/**
 * Class InMemoryRoleProvider
 * @package Dot\Rbac\Role
 */
class InMemoryRoleProvider implements RoleProviderInterface
{
    /** @var array */
    protected $roles = [];

    /** @var array */
    protected $rolesConfig = [];

    /**
     * InMemoryRoleProvider constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['roles']) && is_array($options['roles'])) {
            $this->rolesConfig = $options['roles'];
        }
    }

    /**
     * @param array $roleNames
     * @return array
     */
    public function getRoles(array $roleNames): array
    {
        $roles = [];

        foreach ($roleNames as $roleName) {
            $roles[] = $this->getRole($roleName);
        }

        return $roles;
    }

    /**
     * @param string $roleName
     * @return RoleInterface
     */
    public function getRole(string $roleName): RoleInterface
    {
        if (isset($this->roles[$roleName])) {
            return $this->roles[$roleName];
        }

        //if no config, create a simple role with no permission
        if (!isset($this->rolesConfig[$roleName])) {
            $role = new Role($roleName);
            $this->roles[$roleName] = $role;
            return $role;
        }

        $roleConfig = $this->rolesConfig[$roleName];
        if (isset($roleConfig['children'])) {
            $role = new HierarchicalRole($roleName);
            $childRoles = (array)$roleConfig['children'];
            foreach ($childRoles as $childRole) {
                $childRole = $this->getRole($childRole);
                $role->addChild($childRole);
            }
        } else {
            $role = new Role($roleName);
        }
        $permissions = $roleConfig['permissions'] ?? [];
        foreach ($permissions as $permission) {
            $role->addPermission($permission);
        }
        $this->roles[$roleName] = $role;
        return $role;
    }
}
