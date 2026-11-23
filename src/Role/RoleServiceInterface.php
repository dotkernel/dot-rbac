<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;

interface RoleServiceInterface
{
    public function getIdentity(): mixed;

    public function getGuestRole(): string;

    /**
     * @return array<RoleInterface>
     */
    public function getIdentityRoles(): array;

    /**
     * @param array<string|RoleInterface> $roles
     */
    public function matchIdentityRoles(array $roles): bool;

    /**
     * Converts role names to role objects. Role objects stay as they are.
     *
     * @param array<mixed> $roles
     * @return array<RoleInterface>
     */
    public function resolveRoles(array $roles): array;
}
