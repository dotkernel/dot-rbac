<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AssertionPluginManagerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AssertionPluginManager
    {
        $config = $container->get('config')['dot_authorization']['assertion_manager'];
        return new AssertionPluginManager($container, $config);
    }
}
