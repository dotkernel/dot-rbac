<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Authorization\Role\RoleInterface;

interface RoleProviderInterface
{
    /**
     * @param array<string> $roleNames
     * @return array<RoleInterface>
     */
    public function getRoles(array $roleNames): array;
}
