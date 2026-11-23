<?php

declare(strict_types=1);

namespace Dot\Rbac;

use Dot\Authorization\Role\RoleInterface;

interface RbacInterface
{
    /**
     * @param array<RoleInterface> $roles
     */
    public function isGranted(string $permission, array $roles): bool;
}
