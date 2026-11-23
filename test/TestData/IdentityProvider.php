<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Dot\Rbac\Identity\IdentityProviderInterface;

final class IdentityProvider implements IdentityProviderInterface
{
    public function __construct(private readonly mixed $identity = null)
    {
    }

    public function getIdentity(): mixed
    {
        return $this->identity;
    }
}
