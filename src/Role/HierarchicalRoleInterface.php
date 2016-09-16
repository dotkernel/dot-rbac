<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 7:09 PM
 */

namespace Dot\Rbac\Role;

use N3vrax\DkAuthorization\Role\RoleInterface;

interface HierarchicalRoleInterface extends RoleInterface
{
    /**
     * Check if the role has children
     *
     * @return bool
     */
    public function hasChildren();

    /**
     * Get child roles
     *
     * @return RoleInterface[]|\Traversable
     */
    public function getChildren();
}