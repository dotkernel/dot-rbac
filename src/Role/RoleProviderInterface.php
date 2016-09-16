<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 9:19 PM
 */

namespace Dot\Rbac\Role;
use N3vrax\DkAuthorization\Role\RoleInterface;

/**
 * A role provider is an object that collect roles from strings and convert them to RoleInterface instances
 *
 * Data can come from anywhere, like a database or memory
 *
 * Interface RoleProviderInterface
 * @package N3vrax\DkRbac\Role
 */
interface RoleProviderInterface
{
    /**
     * @param string[] $roleNames
     * @return RoleInterface[]
     */
    public function getRoles(array $roleNames);
}