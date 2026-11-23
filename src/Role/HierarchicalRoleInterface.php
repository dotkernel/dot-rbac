<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

interface HierarchicalRoleInterface extends RoleInterface
{
    public function hasChildren(): bool;

    /**
     * @return array<string, RoleInterface>
     */
    public function getChildren(): array;
}
