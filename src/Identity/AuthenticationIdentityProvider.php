<?php

declare(strict_types=1);

namespace Dot\Rbac\Identity;

use Laminas\Authentication\AuthenticationServiceInterface;

class AuthenticationIdentityProvider implements IdentityProviderInterface
{
    protected AuthenticationServiceInterface $authentication;

    public function __construct(AuthenticationServiceInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    public function getIdentity(): mixed
    {
        return $this->authentication->getIdentity();
    }
}
