<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/7/2016
 * Time: 10:24 PM
 */

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
    public function isGranted($roles, $permission);
}