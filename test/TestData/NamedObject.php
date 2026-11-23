<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

/**
 * An object with getName() that does not implement RoleInterface.
 */
final class NamedObject
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
