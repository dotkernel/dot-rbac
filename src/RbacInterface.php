<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

declare(strict_types = 1);

namespace Dot\Rbac;

use Dot\Authorization\Role\RoleInterface;

/**
 * Interface RbacInterface
 * @package Dot\Rbac
 */
interface RbacInterface
{
    /**
     * @param RoleInterface[] $roles
     * @param string $permission
     * @return bool
     */
    public function isGranted(string $permission, array $roles): bool;
}
