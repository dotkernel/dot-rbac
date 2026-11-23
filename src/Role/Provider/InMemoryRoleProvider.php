<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Role;

use function is_array;
use function sprintf;

/**
 * @phpstan-type RoleConfig array{children?: string|array<string>, permissions?: array<string>}
 */
class InMemoryRoleProvider implements RoleProviderInterface
{
    /** @var array<string, RoleInterface> */
    protected array $roles = [];

    /** @var array<string, RoleConfig> */
    protected array $rolesConfig = [];

    /** @var array<string, true> */
    private array $building = [];

    /**
     * @param array{roles?: array<string, RoleConfig>}|null $options
     */
    public function __construct(?array $options = null)
    {
        $options = $options ?? [];
        if (isset($options['roles']) && is_array($options['roles'])) {
            $this->rolesConfig = $options['roles'];
        }
    }

    /**
     * @param array<string> $roleNames
     * @return array<RoleInterface>
     */
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

        if (isset($this->building[$roleName])) {
            throw new RuntimeException(sprintf('Circular role hierarchy found at role "%s"', $roleName));
        }

        $this->building[$roleName] = true;
        try {
            $roleConfig = $this->rolesConfig[$roleName];
            if (isset($roleConfig['children'])) {
                $role = new HierarchicalRole($roleName);
                foreach ((array) $roleConfig['children'] as $childRole) {
                    $role->addChild($this->getRole($childRole));
                }
            } else {
                $role = new Role($roleName);
            }

            foreach ($roleConfig['permissions'] ?? [] as $permission) {
                $role->addPermission($permission);
            }
        } finally {
            unset($this->building[$roleName]);
        }

        $this->roles[$roleName] = $role;
        return $role;
    }
}
