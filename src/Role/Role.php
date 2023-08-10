<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

class Role implements RoleInterface
{
    protected string $name;

    protected array $permissions = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function addPermission(string $permission): void
    {
        $this->permissions[$permission] = $permission;
    }

    public function hasPermission(string $permission): bool
    {
        return isset($this->permissions[$permission]);
    }
}
