<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Options\AuthorizationOptions;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthorizationOptionsFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AuthorizationOptions
    {
        return new AuthorizationOptions($container->get('config')['dot_authorization']);
    }
}
