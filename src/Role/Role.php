<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

class Role implements PermissionRoleInterface
{
    /** @var array<string, string> */
    protected array $permissions = [];

    public function __construct(protected string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
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
