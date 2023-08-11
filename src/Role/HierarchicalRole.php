<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

class HierarchicalRole extends Role implements HierarchicalRoleInterface
{
    protected array $children = [];

    public function hasChildren(): bool
    {
        return ! empty($this->children);
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(RoleInterface $child): void
    {
        $this->children[$child->getName()] = $child;
    }
}
