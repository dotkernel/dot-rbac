<?php

declare(strict_types=1);

namespace Dot\Rbac\Identity;

use Laminas\Authentication\AuthenticationServiceInterface;

class AuthenticationIdentityProvider implements IdentityProviderInterface
{
    public function __construct(protected AuthenticationServiceInterface $authentication)
    {
    }

    public function getIdentity(): mixed
    {
        return $this->authentication->getIdentity();
    }
}
