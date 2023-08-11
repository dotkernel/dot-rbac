<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthenticationIdentityProviderFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AuthenticationIdentityProvider
    {
        return new AuthenticationIdentityProvider($container->get(AuthenticationService::class));
    }
}
