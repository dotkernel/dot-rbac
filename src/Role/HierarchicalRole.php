<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

class HierarchicalRole extends Role implements HierarchicalRoleInterface
{
    /** @var array<string, RoleInterface> */
    protected array $children = [];

    public function hasChildren(): bool
    {
        return $this->children !== [];
    }

    /**
     * @return array<string, RoleInterface>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(RoleInterface $child): void
    {
        $this->children[$child->getName()] = $child;
    }
}
