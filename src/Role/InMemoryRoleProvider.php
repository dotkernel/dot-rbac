<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/17/2016
 * Time: 9:48 PM
 */

namespace Dot\Rbac\Role;

class InMemoryRoleProvider implements RoleProviderInterface
{

    protected $roles = [];

    protected $rolesConfig = [];

    public function __construct(array $config)
    {
        $this->rolesConfig = $config;
    }

    public function getRoles(array $roleNames)
    {
        $roles = [];

        foreach ($roleNames as $roleName)
        {
            $roles[] = $this->getRole($roleName);
        }

        return $roles;
    }

    public function getRole($roleName)
    {
        if(isset($this->roles[$roleName])) {
            return $this->roles[$roleName];
        }

        //if no config, create a simple role with no permission
        if(!isset($this->rolesConfig[$roleName]))
        {
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
        $permissions = isset($roleConfig['permissions']) ? $roleConfig['permissions'] : [];
        foreach ($permissions as $permission) {
            $role->addPermission($permission);
        }
        $this->roles[$roleName] = $role;
        return $role;

    }
}