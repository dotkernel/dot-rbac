<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

/**
 * A role provider is an object that collect roles from strings and convert them to RoleInterface instances
 *
 * Data can come from anywhere, like a database or memory
 *
 * Interface RoleProviderInterface
 * @package Dot\Rbac\Role
 */
interface RoleProviderInterface
{
    /**
     * @param string[] $roleNames
     * @return RoleInterface[]
     */
    public function getRoles(array $roleNames);
}