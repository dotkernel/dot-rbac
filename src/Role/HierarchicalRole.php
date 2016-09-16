<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 7:15 PM
 */

namespace Dot\Rbac\Role;

use N3vrax\DkAuthorization\Role\RoleInterface;

class HierarchicalRole extends Role implements HierarchicalRoleInterface
{
    protected $children = [];

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @return RoleInterface[]|\Traversable
     */
    public function getChildren()
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