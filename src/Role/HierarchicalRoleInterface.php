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
 * Interface HierarchicalRoleInterface
 * @package Dot\Rbac\Role
 */
interface HierarchicalRoleInterface extends RoleInterface
{
    /**
     * Check if the role has children
     *
     * @return bool
     */
    public function hasChildren(): bool;

    /**
     * Get child roles
     *
     * @return RoleInterface[]|\Traversable
     */
    public function getChildren(): array;
}
