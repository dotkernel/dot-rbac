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
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param string $permission
     */
    public function addPermission(string $permission)
    {
        $this->permissions[$permission] = $permission;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return isset($this->permissions[$permission]);
    }
}
