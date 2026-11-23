<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

/**
 * A role that holds permissions. Rbac checks permissions only on roles that implement this interface.
 */
interface PermissionRoleInterface extends RoleInterface
{
    /**
     * @return array<string, string>
     */
    public function getPermissions(): array;

    public function hasPermission(string $permission): bool;
}
