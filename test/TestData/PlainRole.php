<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Dot\Authorization\Role\RoleInterface;

/**
 * A role that does not hold permissions.
 */
final class PlainRole implements RoleInterface
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasPermission(string $permission): bool
    {
        return true;
    }
}
