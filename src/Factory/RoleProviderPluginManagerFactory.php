<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RoleProviderPluginManagerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RoleProviderPluginManager
    {
        $config = $container->get('config')['dot_authorization']['role_provider_manager'];
        return new RoleProviderPluginManager($container, $config);
    }
}
