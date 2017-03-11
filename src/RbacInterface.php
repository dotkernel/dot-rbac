<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
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
