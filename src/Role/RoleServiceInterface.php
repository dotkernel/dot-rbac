<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
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