<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

interface HierarchicalRoleInterface extends RoleInterface
{
    public function hasChildren(): bool;

    public function getChildren(): array;
}
