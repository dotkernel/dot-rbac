<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
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
