<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Role;

use function is_array;

class InMemoryRoleProvider implements RoleProviderInterface
{
    protected array $roles       = [];
    protected array $rolesConfig = [];

    public function __construct(?array $options = null)
    {
        $options = $options ?? [];
        if (isset($options['roles']) && is_array($options['roles'])) {
            $this->rolesConfig = $options['roles'];
        }
    }

    public function getRoles(array $roleNames): array
    {
        $roles = [];

        foreach ($roleNames as $roleName) {
            $roles[] = $this->getRole($roleName);
        }

        return $roles;
    }

    public function getRole(string $roleName): RoleInterface
    {
        if (isset($this->roles[$roleName])) {
            return $this->roles[$roleName];
        }

        if (! isset($this->rolesConfig[$roleName])) {
            $role                   = new Role($roleName);
            $this->roles[$roleName] = $role;
            return $role;
        }

        $roleConfig = $this->rolesConfig[$roleName];
        if (isset($roleConfig['children'])) {
            $role       = new HierarchicalRole($roleName);
            $childRoles = (array) $roleConfig['children'];
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
