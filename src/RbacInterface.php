<?php

declare(strict_types=1);

namespace Dot\Rbac;

interface RbacInterface
{
    public function isGranted(string $permission, array $roles): bool;
}
