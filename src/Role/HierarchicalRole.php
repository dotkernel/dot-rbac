<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

/**
 * Class HierarchicalRole
 * @package Dot\Rbac\Role
 */
class HierarchicalRole extends Role implements HierarchicalRoleInterface
{
    /** @var array */
    protected $children = [];

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * @return RoleInterface[]|\Traversable
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param RoleInterface $child
     */
    public function addChild(RoleInterface $child)
    {
        $this->children[$child->getName()] = $child;
    }
}
