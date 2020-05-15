<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Identity\IdentityInterface;
use Dot\Authorization\Role\RoleInterface;

interface RoleServiceInterface
{
    /**
     * @return mixed|null
     */
    public function getIdentity();

    /**
     * @return string
     */
    public function getGuestRole(): string;

    /**
     * @return RoleInterface[]
     */
    public function getIdentityRoles(): array;

    /**
     * @param array $roles
     * @return bool
     */
    public function matchIdentityRoles(array $roles): bool;
}
