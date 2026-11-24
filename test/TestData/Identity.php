<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Dot\Authorization\Identity\IdentityInterface;
use Dot\Authorization\Role\RoleInterface;

final class Identity implements IdentityInterface
{
    /**
     * @param array<string|RoleInterface> $roles
     */
    public function __construct(private readonly array $roles)
    {
    }

    /**
     * @return array<string|RoleInterface>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
