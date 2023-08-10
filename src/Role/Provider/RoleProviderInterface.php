<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

interface RoleProviderInterface
{
    public function getRoles(array $roleNames): array;
}
