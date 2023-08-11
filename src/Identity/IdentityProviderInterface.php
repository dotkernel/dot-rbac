<?php

declare(strict_types=1);

namespace Dot\Rbac\Identity;

interface IdentityProviderInterface
{
    public function getIdentity(): mixed;
}
