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
 * Class Role
 * @package Dot\Rbac\Role
 */
class Role implements RoleInterface
{
    /** @var string */
    protected $name;

    /** @var array */
    protected $permissions = [];

    /**
     * Role constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = (string)$name;
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
        $this->permissions[(string)$permission] = $permission;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return isset($this->permissions[(string)$permission]);
    }
}