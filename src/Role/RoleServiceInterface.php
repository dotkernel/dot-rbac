<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

interface RoleServiceInterface
{
    public function getIdentity();

    public function getGuestRole(): string;

    public function getIdentityRoles(): array;

    public function matchIdentityRoles(array $roles): bool;
}
