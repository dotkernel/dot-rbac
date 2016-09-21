<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 9/21/2016
 * Time: 8:41 PM
 */

namespace Dot\Rbac\Role;


use Dot\Authorization\Identity\IdentityInterface;
use Dot\Authorization\Role\RoleInterface;

interface RoleServiceInterface
{
    /**
     * @return IdentityInterface
     */
    public function getIdentity();

    /**
     * @return string
     */
    public function getGuestRole();

    /**
     * @return RoleInterface[]
     */
    public function getIdentityRoles();

    /**
     * @param array $roles
     * @return bool
     */
    public function matchIdentityRoles(array $roles);
}