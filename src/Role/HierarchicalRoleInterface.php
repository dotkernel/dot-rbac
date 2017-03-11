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
