<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 7:11 PM
 */

namespace Dot\Rbac\Role;

use N3vrax\DkAuthorization\Role\RoleInterface;

class Role implements RoleInterface
{
    protected $name;

    protected $permissions = [];

    public function __construct($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param string $permission
     */
    public function addPermission($permission)
    {
        $this->permissions[(string) $permission] = $permission;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return isset($this->permissions[(string) $permission]);
    }
}