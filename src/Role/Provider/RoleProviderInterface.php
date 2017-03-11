<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role\Provider;

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
    public function getRoles(array $roleNames): array;
}
